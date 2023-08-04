<?php

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 17:13:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;

desc('🚡 Migrating database');
task('deploy:migrate', function () {
    artisan('migrate --database=backup --path=database/migrations/backup', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --path=database/migrations/landlord', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --path=database/migrations/tenant', ['skipIfNoEnv', 'showOutput'])();
});


desc('🌱 Seeding database');
task('install:seeding', function () {
    artisan('db:seed', ['skipIfNoEnv', 'showOutput'])();
});

desc('🌱 Symlink private folder to resources dir');
task('deploy:shared-private', function () {
    $sharedPath = "{{deploy_path}}/shared";
    $dir='private';
    run("{{bin/symlink}} $sharedPath/$dir {{release_path}}/resources/$dir");

});

desc('🏗️ Build vue app');
task('deploy:build', function () {
    run("cd {{release_path}} && {{bin/npm}} run build");

});

desc('⚙️ Copy env master file');
task('deploy:copy-env', function () {
    $sharedPath = "{{deploy_path}}/shared";
    run("cp artifacts/env.{{environment}} $sharedPath/.env");

});


