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

class ResetWebsiteFooter
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        data_set(
            $modelData,
            'footer',
            [
                'type'      => 'simple',
                'columns'   => $this->getColumns(),
                'copyright' => $this->getCopyright(),
                'social'    => $this->getSocialLinks(),
                'logo'      => $this->getFooterLogo($website)
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


    private function getFooterLogo(Website $website)
    {
        return AttachImageToWebsite::run(
            website: $website,
            collection: 'footer-logo',
            imagePath: resource_path('art/logo/logo-white-square.png'),
            originalFilename: 'footer-logo.png'
        )->id;
    }

    private function getColumns(): array
    {
        return [
            [
                'id'    => Str::ulid(),
                'type'  => 'list',
                'label' => 'Column A',
                'items' => [
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link A1'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link A2'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link A3'
                    ]

                ]
            ],
            [
                'type'  => 'list',
                'id'    => Str::ulid(),
                'label' => 'Column B',
                'items' => [
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link B1'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link B2'
                    ],
                    [
                        'id'    => Str::ulid(),
                        'href'  => '#',
                        'label' => 'Link B3'
                    ]

                ]
            ],
            [
                'id'    => Str::ulid(),
                'type'  => 'info',
                'items' => [
                    [
                        'id'   => Str::ulid(),
                        'type' => 'phone',
                        'data' => '+421 (0)33 000 00 00'
                    ],
                    [
                        'id'   => Str::ulid(),
                        'type' => 'email',
                        'data' => 'contact@exmple.com'
                    ],
                    [
                        'id'   => Str::ulid(),
                        'type' => 'other',
                        'data' => [
                            'icon'    => 'fas fa-star',
                            'tooltip' => 'hello world',
                            'label'   => __('Hello 👋🏻')
                        ]

                    ]
                ]
            ]
        ];
    }

    private function getCopyright(): array
    {
        return [
            'label'     => 'Wowsbar',
            'startYear' => 2023
        ];
    }

    private function getSocialLinks(): array
    {
        return [
            [
                'id'   => Str::ulid(),
                'label' => 'facebook',
                'icon' => 'fab fa-facebook',
                'href' => '#'
            ],
        ];
    }


    public function getCommandSignature(): string
    {
        return 'website:reset-footer {website}';
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
