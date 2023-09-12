<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 22 Aug 2023 10:04:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


declare(strict_types=1);

namespace Deployer;

require_once __DIR__.'/../vendor/autoload.php';

use Webmozart\Assert\Assert;
use Symfony\Component\Finder\Finder;

use function file_get_contents;

/**
 * The supervisor(ctl) binary
 */
set('bin/supervisor', static function (): string {
    $binary = which('supervisorctl');
    Assert::string($binary);

    return $binary;
});

/**
 * This is the directory where you have your supervisor configs
 */
set('supervisor_source_dir', 'deploy/etc/supervisor');

/**
 * This is the directory on your server where the final config file will be uploaded
 */
set('supervisor_remote_dir', '/etc/supervisor/conf.d');

/**
 * This library will create a single final config file for supervisor. This will be the name of that file
 */
set('supervisor_config_filename', '{{application}}-{{environment}}.conf');

/**
 * Contains an array of config files to exclude.
 * You can use this to exclude files based on stage for example
 */
set('supervisor_excluded_files', []);

/**
 * Whether to use a group based approach or not. Remember to also set supervisor_groups
 */
set('supervisor_group_based', false);

/**
 * The groups to start/stop if supervisor_group_based is true
 */
set('supervisor_groups', []);

task('supervisor:stop', static function (): void {
    if (get('supervisor_group_based') === true) {
        $groups = get('supervisor_groups');
        Assert::isArray($groups);
        Assert::allString($groups);

        foreach ($groups as $group) {
            run(sprintf('{{bin/supervisor}} stop %s:*', $group));
        }
    } else {
        run('sudo {{bin/supervisor}} stop all');
    }
})->desc('Stops all services managed by Supervisor');

task('supervisor:remove', static function (): void {
    run('sudo rm -rf {{supervisor_remote_dir}}/{{supervisor_config_filename}}');
    run('sudo {{bin/supervisor}} stop horizon-{{application}}-{{alias}}');
})->desc('Remove config file');

task('supervisor:upload', static function (): void {
    $sourceDir = get('supervisor_source_dir');
    Assert::string($sourceDir);

    $finder = new Finder();
    $finder->files()->in($sourceDir);

    $excludedFiles = get('supervisor_excluded_files');
    Assert::isArray($excludedFiles);

    $mergedConfigs = '';
    foreach ($finder as $file) {
        if (in_array($file->getFilename(), $excludedFiles, true)) {
            continue;
        }

        $mergedConfigs .= trim(file_get_contents($file->getRealPath()))."\n\n";
    }

    if ('' === $mergedConfigs) {
        run('sudo rm -rf {{supervisor_remote_dir}}/{{supervisor_config_filename}}');

        return;
    }

    run("mkdir -p artifacts/supervisor");
    run("echo -e \"$mergedConfigs\" > artifacts/supervisor/{{supervisor_config_filename}} ");
    run("sudo cp artifacts/supervisor/{{supervisor_config_filename}} {{supervisor_remote_dir}}/{{supervisor_config_filename}} ");

})->desc('This task uploads your processed supervisor configs to the specified directory on your server');

task('supervisor:start', static function (): void {
    if (get('supervisor_group_based') === true) {
        $groups = get('supervisor_groups');
        Assert::isArray($groups);
        Assert::allString($groups);

        foreach ($groups as $group) {
            run(sprintf('{{bin/supervisor}} update %s', $group));
            run(sprintf('{{bin/supervisor}} start %s:*', $group));
        }
    } else {
        run('sudo {{bin/supervisor}} update');
        run('sudo {{bin/supervisor}} start all');
    }
})->desc('Starts all services managed by Supervisor');

task('supervisor:reread-update', static function (): void {
    if (get('supervisor_group_based') === true) {
        $groups = get('supervisor_groups');
        Assert::isArray($groups);
        Assert::allString($groups);

        foreach ($groups as $group) {
            run(sprintf('{{bin/supervisor}} reread %s', $group));
            run(sprintf('{{bin/supervisor}} update %s:*', $group));
        }
    } else {
        run('sudo {{bin/supervisor}} reread');
        run('sudo {{bin/supervisor}} update');
    }
})->desc('Reread conf files and update supervisor');
