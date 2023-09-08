<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Sep 2023 19:51:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Deployer;


desc('Deploys your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:migrate',
    'deploy:publish',
]);
