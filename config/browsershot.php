<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Exécutable Chrome
    |--------------------------------------------------------------------------
    |
    | Chemin du navigateur utilisé par Browsershot pour générer les PDF.
    | Laisser vide pour la détection automatique (Chrome puis Edge dans
    | « Program Files »). Si rien n'est trouvé, Puppeteer utilise le Chrome
    | qu'il a lui-même téléchargé — ce qui échoue sous IIS, dont le pool
    | d'applications n'a pas de profil utilisateur exploitable.
    |
    */

    'chrome_path' => env('BROWSERSHOT_CHROME_PATH'),

    /*
    |--------------------------------------------------------------------------
    | Cache Puppeteer
    |--------------------------------------------------------------------------
    |
    | Emplacement où Puppeteer cherche le navigateur qu'il a téléchargé. Par
    | défaut il vise le profil du compte qui exécute PHP, soit
    | C:\Windows\system32\config\systemprofile\.cache\puppeteer pour le pool
    | IIS. Renseigner un dossier partagé et lisible par ce compte si l'on
    | préfère `npx puppeteer browsers install chrome` à un Chrome système.
    |
    */

    'puppeteer_cache_dir' => env('PUPPETEER_CACHE_DIR'),

];
