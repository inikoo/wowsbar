<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Sep 2023 11:04:38 Malaysia Time, Bali Airport, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;

class ResetWebsiteLayout
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        data_set(
            $modelData,
            'layout',
            [
                "layout"      => "full",
                "favicon"     => $this->getFavicon($website),
                "colorLayout" => "rgba(255 255 255)",
                "imageLayout" => null,
                "header"      => [
                    "color"        => "rgba(255 255 255)",
                     "colorScheme" => "gray"
                ],
                "content"     => [
                    "color"       => "rgba(255 255 255)",
                    "colorScheme" => "gray"
                ],
                "footer"      => [
                    "color"       => "rgba(255 255 255)",
                    "colorScheme" => "gray"
                ]
            ]
        );
        $website->update($modelData);

        $website->update(
            [
                'compiled_structure' => $website->getCompiledStructure()
            ]
        );

        return $website;
    }


    private function getFavicon(Website $website)
    {
        return AttachImageToWebsite::run(
            website: $website,
            collection: 'favicon',
            imagePath: resource_path('art/logo/logo-charcoal-square.png'),
            originalFilename: 'favicon.png'
        )->id;
    }


    public function getCommandSignature(): string
    {
        return 'website:reset-layout {website}';
    }

    public function asCommand(Command $command): int
    {
        try {
            $website = Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->handle($website);

        return 0;
    }

}
