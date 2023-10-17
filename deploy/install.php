<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 17:13:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;

use Symfony\Component\Console\Output\OutputInterface;

require 'contrib/crontab.php';

desc('💀 Delete working directory');
task('install:delete_deploy_path', function () {
    run('rm -rf {{deploy_path}}');
});

desc('✨ Resetting database');
task('install:reset-db', function () {
    run('dropdb --force --if-exists {{db}}');
    run('createdb --template=template0 --lc-collate={{db_collate}} --lc-ctype={{db_collate}} {{db}}');
    run('dropdb --force --if-exists {{db_backup}}');
    run('createdb --template=template0 --lc-collate={{db_collate}} --lc-ctype={{db_collate}} {{db_backup}}');
    run('redis-cli KEYS "wowsbar_*" | xargs redis-cli DEL');
});

desc('🌱 Copy artifacts no present in repo');
task('install:copy-artifacts', function () {
    $copyVerbosity = output()->getVerbosity() === OutputInterface::VERBOSITY_DEBUG ? 'v' : '';
    $sharedPath    = "{{deploy_path}}/shared";
    run("cp artifacts/env.{{environment}} $sharedPath/.env");
    run("cp -r$copyVerbosity artifacts/private $sharedPath");
});

desc('🪴 install set up');
task('install:setup', function () {
    run("sudo gpasswd -a www-data {{remote_user}}");
    run("sudo mkdir -p {{root_path}}");
    run("sudo chown {{remote_user}}:{{http_group}} {{root_path}} ");
    run("{{bin/symlink}} {{deploy_path}} {{environment}}");
});

desc('🎨 install artifacts');
task('install:artifacts', function () {
    run("cp artifacts/install/* {{deploy_path}}/current ");
});

add('crontab:jobs', [
    '* * * * * cd {{current_path}} && {{bin/php}} artisan schedule:run >> /dev/null 2>&1',
]);

desc('Clean up supervisor');
task('install:clean-supervisor', [
    'supervisor:remove',
    'supervisor:reread-update',
]);

desc('🚡 Refresh elasticsearch');
task('install:elasticsearch', function () {
    artisan('es:refresh', ['skipIfNoEnv', 'showOutput'])();
});

desc('🌱 Seeding database');
task('install:seeding', function () {
    artisan('db:seed --force', ['skipIfNoEnv', 'showOutput'])();
});

desc('⚙️ Copy env master file');
task('install:copy-env', function () {
    $sharedPath = "{{deploy_path}}/shared";
    run("cp artifacts/env.{{environment}} $sharedPath/.env");
});

desc('✨ run install artifacts');
task('install:run', function () {
    run("cd {{release_path}} && ./install.sh");
});


desc('🚡 Migrating database');
task('install:migrate', function () {
    artisan('migrate --force --database=backup --path=database/migrations/backup', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --force', ['skipIfNoEnv', 'showOutput'])();
});

desc('🏗️ Build vue app');
task('install:build', function () {
    run("cd {{release_path}} && {{bin/npm}} run build");
});

set('shared_dirs', ['storage','private']);


desc('Install wowsbar');
task('install', [
    'install:env',
    'install:delete_deploy_path',
    'install:reset-db',
    'deploy:info',
    'install:setup',
    'deploy:setup',
    'deploy:lock',
    'install:copy-artifacts',
    'install:copy-env',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'install:migrate',
    'install:seeding',
    'artisan:key:generate',
    'artisan:route:clear',
    'artisan:cache:clear',
    'artisan:config:clear',
    'install:elasticsearch',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:horizon:clear',
    'install:build',
    'deploy:publish',
    'supervisor:upload',
    'supervisor:reread-update',
    'nginx:upload',
    'nginx:enable-site',
    'nginx:restart',
    'crontab:sync',
    'install:artifacts',
    'install:run'
]);
