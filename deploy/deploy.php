<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Sep 2023 19:51:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;

desc('🚡 Migrating database');
task('deploy:migrate', function () {
    artisan('migrate --force --database=backup --path=database/migrations/backup', ['skipIfNoEnv', 'showOutput'])();
    artisan('migrate --force', ['skipIfNoEnv', 'showOutput'])();
});
desc('🏗️ Build vue app');
task('deploy:build', function () {
    run("cd {{release_path}} && {{bin/npm}} run build");
});

desc('Set release');
task('deploy:set-release', function () {
    run("cd {{release_path}} && sed -i~ '/^RELEASE=/s/=.*/=\"{{release_semver}}\"/' .env   ");
});

set('shared_dirs', ['storage','private']);
set('shared_files', ['.env','install.sh']);

desc('Deploys your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy:set-release',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:migrate',
    'deploy:build',
    'deploy:publish',
    'artisan:horizon:terminate'
]);
