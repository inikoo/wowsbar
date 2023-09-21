<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;

class ResetWebsiteStructure
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {


        data_set($modelData, 'header', $this->getInitialHeader($website));
        data_set($modelData, 'layout', $this->getInitialLayout($website));

        $website->update($modelData);


        /*
        $structure = $website->structure;
        foreach (Storage::disk('datasets')->files('website-structure/'.$website->type) as $file) {
            preg_match('/\/(\w+).json/', $file, $field);
            $structure[$field[1]] = json_decode(Storage::disk('datasets')->get($file), true);
            data_set(
                $structure,
                $field[1],
                json_decode(Storage::disk('datasets')->get($file), true),
                overwrite: false
            );
        }


        $modelData = [
            'structure' => $structure
        ];

        */


        $website->update(
            [
                'compiled_structure' => $website->getCompiledStructure()
            ]
        );
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("supervisor.website");
    }


    public function getInitialHeader($website): array
    {

        $logo=AttachImageToWebsite::run(
            website: $website,
            collection: 'logo',
            imagePath: resource_path('art/logo/logo-teal.png'),
            originalFilename: 'logo.png'
        );

        return  [
            'component'=> 'simpleSticky',
            'logo'     => $logo->id
        ];
    }

    public function getInitialLayout($website): array
    {

        $favicon=AttachImageToWebsite::run(
            website: $website,
            collection: 'logo',
            imagePath: resource_path('art/favicons/wowsbar-website-favicon-color-180x180.png'),
            originalFilename: 'favicon.png'
        );


        return [
            "layout"      => "full",
            "favicon"     => $favicon->id,
            "colorLayout" => "rgba(55 65 81)",
            "imageLayout" => null,
            "header"      => [
                "color" => "rgba(55 65 81)"
            ],
            "content"     => [
                "color" => "rgba(55 65 81)"
            ],
            "footer"      => [
                "color" => "rgba(55 65 81)"
            ]
        ];




    }


    public function asController(Website $website, ActionRequest $request): Website
    {

        return $this->handle($website);
    }

    public function getCommandSignature(): string
    {
        return 'website:reset-structure {website}';
    }

    public function asCommand(Command $command): int
    {

        try {
            $website=Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());
            return 1;
        }

        $this->handle($website);

        return 0;
    }

}
