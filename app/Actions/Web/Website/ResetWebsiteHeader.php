<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Sep 2023 10:44:54 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ResetWebsiteHeader
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        data_set(
            $modelData,
            'header',
            [
                'type'      => 'simpleSticky',
                'menu'      => $this->getMenu(),
                'logo'      => $this->geHeaderLogo($website)
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


    private function geHeaderLogo(Website $website)
    {
        return AttachImageToWebsite::run(
            website: $website,
            collection: 'header-logo',
            imagePath: resource_path('art/logo/logo-charcoal.png'),
            originalFilename: 'header-logo.png'
        )->id;
    }

    private function getMenu(): array
    {
        return
            [
                'type'  => 'simple',
                'items' => [
                    [
                        'id'    => Str::ulid(),
                        'type'  => 'link',
                        'icon'  => 'far fa-dot-circle',
                        'label' => 'Link A',
                        'href'  => '#'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'type'  => 'link',
                        'icon'  => 'far fa-dot-circle',
                        'label' => 'Link B',
                        'href'  => '#'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'type'  => 'link',
                        'icon'  => 'far fa-dot-circle',
                        'label' => 'Link C',
                        'href'  => '#'
                    ],
                ]
            ];
    }


    public function getCommandSignature(): string
    {
        return 'website:reset-header {website}';
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
