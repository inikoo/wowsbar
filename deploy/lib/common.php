<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 22 Aug 2023 17:23:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;

desc('🚡 Migrating database');
task('deploy:migrate', function () {
    artisan('migrate --force --database=backup --path=database/migrations/backup', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --force --path=database/migrations/landlord', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --force --path=database/migrations/tenant', ['skipIfNoEnv', 'showOutput'])();
});

desc('🚡 Deploy elasticsearch');
task('deploy:elasticsearch', function () {
    artisan('es:refresh', ['skipIfNoEnv', 'showOutput'])();
});


desc('🌱 Seeding database');
task('install:seeding', function () {
    artisan('db:seed --force', ['skipIfNoEnv', 'showOutput'])();
});

desc('🌱 Symlink private folder to resources dir');
task('deploy:shared-private', function () {
    $sharedPath = "{{deploy_path}}/shared";
    $dir        ='private';
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
