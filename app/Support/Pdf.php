<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\Browsershot\Browsershot;

final class Pdf
{
    /**
     * Emplacements testés quand aucun chemin n'est configuré, par ordre de
     * préférence : Chrome puis Edge (présent d'office sur Windows Server).
     *
     * @var array<int, string>
     */
    private const CANDIDATES = [
        '%s\\Google\\Chrome\\Application\\chrome.exe',
        '%s\\Microsoft\\Edge\\Application\\msedge.exe',
    ];

    /**
     * Retourne un Browsershot préconfiguré pour la prod Windows/IIS.
     *
     * - Redirige le dossier temporaire (TMP/TEMP) vers un emplacement inscriptible
     *   par le pool d'applications IIS. Sans ça, Symfony Process échoue à écrire la
     *   sortie du process : « fopen(C:\Windows\TEMP\sf_proc_*.lock): Permission denied ».
     * - Fixe l'exécutable Chrome : sous IIS, le cache Puppeteer par défaut pointe
     *   vers le profil du compte de service (« Could not find Chrome »).
     * - noSandbox() : requis par Chrome headless sous Windows Server.
     * - timeout(120) : laisse le temps à Chrome de démarrer et rendre.
     * - set_time_limit(180) : le max_execution_time de la prod est à 30 s, soit
     *   moins que le démarrage de Chrome sur un rapport de plusieurs pages
     *   (« Maximum execution time of 30 seconds exceeded »).
     */
    public static function make(string $html): Browsershot
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(180);
        }

        $tmp = storage_path('app/pdftmp');
        if (! is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        foreach (['TMP', 'TEMP', 'TMPDIR'] as $var) {
            self::putEnv($var, $tmp);
        }

        // Cache Puppeteer partagé, si l'on préfère un navigateur téléchargé
        // par `npx puppeteer browsers install chrome` à un Chrome système.
        $cache = config('browsershot.puppeteer_cache_dir');
        if (is_string($cache) && $cache !== '') {
            self::putEnv('PUPPETEER_CACHE_DIR', $cache);
        }

        $browsershot = Browsershot::html($html)
            ->noSandbox()
            ->timeout(120)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground();

        $chrome = self::chromePath();
        if ($chrome !== null) {
            $browsershot->setChromePath($chrome);
        }

        return $browsershot;
    }

    /**
     * Chemin du navigateur : celui configuré, sinon le premier Chrome ou Edge
     * installé sur la machine. `null` laisse Puppeteer résoudre lui-même.
     *
     * Exposé publiquement pour le diagnostic :
     * `php artisan tinker --execute="dump(App\Support\Pdf::chromePath());"`
     */
    public static function chromePath(): ?string
    {
        // 1. Chemin explicite. Un chemin erroné ne bloque pas : on continue la
        //    recherche plutôt que de retomber sur la résolution de Puppeteer,
        //    qui échoue sous IIS.
        $configured = config('browsershot.chrome_path');
        if (is_string($configured) && $configured !== '' && is_file($configured)) {
            return $configured;
        }

        // 2. Navigateur installé sur la machine.
        $roots = array_unique(array_filter([
            getenv('ProgramFiles') ?: null,
            getenv('ProgramFiles(x86)') ?: null,
            'C:\\Program Files',
            'C:\\Program Files (x86)',
        ]));

        foreach (self::CANDIDATES as $candidate) {
            foreach ($roots as $root) {
                $path = sprintf($candidate, rtrim($root, '\\'));

                if (is_file($path)) {
                    return $path;
                }
            }
        }

        // 3. Navigateur téléchargé par `npx puppeteer browsers install chrome`.
        return self::puppeteerChrome();
    }

    /**
     * Dernière version installée dans un cache Puppeteer, dont le format est
     * `<cache>/chrome/win64-<version>/chrome-win64/chrome.exe`.
     */
    private static function puppeteerChrome(): ?string
    {
        $caches = array_unique(array_filter([
            config('browsershot.puppeteer_cache_dir'),
            getenv('PUPPETEER_CACHE_DIR') ?: null,
            ($home = getenv('USERPROFILE') ?: getenv('HOME')) ? $home.'/.cache/puppeteer' : null,
        ]));

        foreach ($caches as $cache) {
            $base = rtrim(str_replace('\\', '/', (string) $cache), '/');

            foreach (['chrome/*/chrome-win64/chrome.exe', 'chrome/*/chrome-linux64/chrome'] as $pattern) {
                $matches = glob($base.'/'.$pattern) ?: [];

                if ($matches !== []) {
                    rsort($matches, SORT_NATURAL);

                    return $matches[0];
                }
            }
        }

        return null;
    }

    /** Renseigne une variable d'environnement pour le process Node lancé ensuite. */
    private static function putEnv(string $name, string $value): void
    {
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
