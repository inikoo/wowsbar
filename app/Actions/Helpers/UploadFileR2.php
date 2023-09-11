<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 11:19:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadFileR2
{
    use AsAction;

    public function handle(string $name,string $disk='r2'): bool|string
    {
        $path = resource_path('art/logo/png/logo-black.png');
        $content = file_get_contents($path);
        return Storage::disk($disk)->put($name, $content);
    }

    public function getCommandSignature(): string
    {
        return 'r2:upload {name}';
    }

    public function asCommand(Command $command): int
    {
        $res=$this->handle($command->argument('name'));
        if($res){
            $command->line('OK');
            return 0;
        }
        return 1;
    }

}
