<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 16 Oct 2023 00:01:26 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';



Dotenv::createImmutable(__DIR__ . '/../')->load();

desc('Inject all necessary .env variables inside deployer config');
task('install:env', function () {

    $websites_urls='';
    foreach(explode(',', env('DEPLOY_WEBSITES_URLS')) as $urls) {
        $websites_urls.=" $urls *.$urls";
    }

    set('nginx_urls', env('DEPLOY_APP_URL').' *.'.env('DEPLOY_APP_URL').$websites_urls);
    set('discord_channel', env('DEPLOY_DISCORD_CHANNEL'));
    set('discord_token', env('DEPLOY_DISCORD_CHANNEL_TOKEN'));
    set('release_semver', env('RELEASE'));

});
