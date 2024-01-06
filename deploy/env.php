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

    $environment=currentHost()->get('environment');
    if($environment=='production') {
        $dotenv = Dotenv::createImmutable(__DIR__, '../.env.wowsbar.production.deploy');
    } else {
        $dotenv = Dotenv::createImmutable(__DIR__, '../.env.wowsbar.staging.deploy');
    }
    $dotenv->load();

    set('remote_user', env('DEPLOY_REMOTE_USER'));
    set('discord_channel', env('DEPLOY_DISCORD_CHANNEL'));
    set('discord_token', env('DEPLOY_DISCORD_CHANNEL_TOKEN'));
    set('release_semver', env('RELEASE'));

});
