<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 20 Jun 2024 17:50:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Media\Media\SaveModelImage;
use App\Actions\Web\WebBlockType\StoreWebBlockType;
use App\Actions\Web\WebBlockType\UpdateWebBlockType;
use App\Models\Web\WebBlockType;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class SeedWebBlockTypes
{
    use AsAction;

    public function handle(): void
    {
        foreach (Storage::disk('datasets')->files('web-block-types') as $file) {
            $rawWebBlockTypeData = Storage::disk('datasets')->json($file);
            $webBlockTypeData    = Arr::only(
                $rawWebBlockTypeData,
                [
                    'scope',
                    'code',
                    'name',
                    'fixed',
                    'blueprint',
                    'data'
                ]
            );

            $additionalData = [];

            if (Arr::has($rawWebBlockTypeData, 'icon')) {
                $additionalData = [
                    'icon' => Arr::get($rawWebBlockTypeData, 'icon')
                ];
            }

            if (Arr::has($rawWebBlockTypeData, 'component')) {
                $additionalData = [
                    'component' => Arr::get($rawWebBlockTypeData, 'component')
                ];
            }


            if ($additionalData != []) {
                $data = array_merge($webBlockTypeData['data'], $additionalData);
                data_set($webBlockTypeData, 'data', $data);
            }


            $webBlockType = WebBlockType::where('code', Arr::get($webBlockTypeData, 'code'))->first();

            if ($webBlockType) {
                $webBlockType = UpdateWebBlockType::run($webBlockType, $webBlockTypeData);
            } else {
                $webBlockType = StoreWebBlockType::run($webBlockTypeData);
            }


            /*$imagePath = 'web-block-types/screenshots/'.$webBlockType->code.'.png';
            if (Storage::disk('datasets')->exists($imagePath)) {
                SaveModelImage::run(
                    $webBlockType,
                    [
                        'path' => Storage::disk('datasets')->path($imagePath),
                        'originalName' => $webBlockType->code.'.png',

                    ],
                    'screenshot'
                );
            }*/
        }
    }

    public string $commandSignature = 'web:seed-web-block-types';

    public function asCommand(Command $command): int
    {
        $this->handle();

        return 0;
    }
}
