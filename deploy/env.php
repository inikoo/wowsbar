<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 05 Jan 2024 14:23:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace Deployer;

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

desc('Inject all necessary .env variables inside deployer config');
task('install:env', function () {

    $environment=currentHost()->get('environment');
    if ($environment=='production') {
        $dotenv = Dotenv::createImmutable(__DIR__, '../.env.wowsbar.production.deploy');
    } else {
        $dotenv = Dotenv::createImmutable(__DIR__, '../.env.wowsbar.staging.deploy');
    }
    $dotenv->load();
    set('remote_user', env('DEPLOY_REMOTE_USER'));
    set('release_semver', env('RELEASE'));
    set('discord_channel', env('DEPLOY_DISCORD_CHANNEL'));
    set('discord_token', env('DEPLOY_DISCORD_CHANNEL_TOKEN'));
});
