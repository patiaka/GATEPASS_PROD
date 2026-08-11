<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\Browsershot\Browsershot;

class Pdf
{
    /**
     * Retourne un Browsershot préconfiguré pour la prod Windows/IIS.
     *
     * - Redirige le dossier temporaire (TMP/TEMP) vers un emplacement inscriptible
     *   par le pool d'applications IIS. Sans ça, Symfony Process échoue à écrire la
     *   sortie du process : « fopen(C:\Windows\TEMP\sf_proc_*.lock): Permission denied ».
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
            putenv("{$var}={$tmp}");
            $_ENV[$var] = $tmp;
            $_SERVER[$var] = $tmp;
        }

        return Browsershot::html($html)
            ->noSandbox()
            ->timeout(120)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground();
    }
}
