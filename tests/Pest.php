<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:40:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use Symfony\Component\Process\Process;
use Tests\TestCase;



uses(TestCase::class,)->in('Feature');

function loadDB($dumpName): void
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../', '.env.testing');
    $dotenv->load();

    $process = new Process(
        [
            __DIR__.'/../devops/devel/reset_test_database.sh',
            env('DB_DATABASE_TEST', 'wowsbar_test'),
            env('DB_PORT'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            $dumpName
        ]
    );
    $process->run();
}

