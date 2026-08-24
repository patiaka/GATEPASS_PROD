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
     */
    public static function make(string $html): Browsershot
    {
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
        $configured = config('browsershot.chrome_path');
        if (is_string($configured) && $configured !== '') {
            return is_file($configured) ? $configured : null;
        }

        $roots = array_filter([
            getenv('ProgramFiles') ?: null,
            getenv('ProgramFiles(x86)') ?: null,
            'C:\\Program Files',
            'C:\\Program Files (x86)',
        ]);

        foreach (self::CANDIDATES as $candidate) {
            foreach (array_unique($roots) as $root) {
                $path = sprintf($candidate, rtrim($root, '\\'));

                if (is_file($path)) {
                    return $path;
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
