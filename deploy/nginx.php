<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 02 Aug 2023 19:29:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

declare(strict_types=1);

namespace Deployer;

require_once __DIR__.'/../vendor/autoload.php';

use Webmozart\Assert\Assert;
use function file_get_contents;
use Symfony\Component\Finder\Finder;


set('http_port', '80');

set('nginx_source_dir', 'deploy/etc/nginx');

set('nginx_remote_available_dir', '/etc/nginx/sites-available');
set('nginx_remote_enabled_dir', '/etc/nginx/sites-enabled');

set('nginx_config_filename', '{{application}}-{{alias}}.conf');

set('nginx_excluded_files', []);


task('nginx:upload', static function (): void {
    $sourceDir = get('nginx_source_dir');
    Assert::string($sourceDir);

    $finder = new Finder();
    $finder->files()->in($sourceDir);

    $excludedFiles = get('nginx_excluded_files');
    Assert::isArray($excludedFiles);

    $mergedConfigs = '';
    foreach ($finder as $file) {
        if (in_array($file->getFilename(), $excludedFiles, true)) {
            continue;
        }
        $mergedConfigs .= trim(file_get_contents($file->getRealPath()))."\n\n";
    }

    if ('' === $mergedConfigs) {
        run('sudo rm -rf {{nginx_remote_available_dir}}/{{nginx_config_filename}}');

        return;
    }
    run("mkdir -p artifacts/nginx");
    run("echo -e \"$mergedConfigs\" > artifacts/nginx/{{nginx_config_filename}}");
    run("sudo cp artifacts/nginx/{{nginx_config_filename}} {{nginx_remote_available_dir}}/{{nginx_config_filename}} ");

})->desc('This task uploads your processed nginx configs to the specified directory on your server');

task('nginx:enable-site', static function (): void {
    run("sudo rm -f {{nginx_remote_enabled_dir}}/{{nginx_config_filename}}");
    run("sudo ln -s {{nginx_remote_available_dir}}/{{nginx_config_filename}} {{nginx_remote_enabled_dir}}/{{nginx_config_filename}}");
})->desc('Enable site');

task('nginx:restart', static function (): void {
    run('sudo systemctl restart nginx.service');
})->desc('Restarts nginx');

