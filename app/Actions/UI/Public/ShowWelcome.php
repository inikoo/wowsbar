<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:30:56 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowWelcome
{
    use AsController;

    public function handle(): Response
    {
        return Inertia::render(
            'Public/Welcome',
            [

                'hero' => [
                    'whatsNew' => [
                        'label' => __('Just shipped v0.9'),
                        'route' => [
                            'name'       => 'public.whats-new',
                            'parameters' => null
                        ]
                    ],
                    'title'    => 'Your One-Stop Platform Banners Creator',
                    'text'     => "Create stunning website banners effortlessly! Easy to use, customizable templates, and no design experience required. Elevate your website's appeal today!",
                    'media'    => [
                        'logo'          => GetPictureSources::run((new Image())->make(url('/images/logo.png'))),
                        'appScreenshot' => GetPictureSources::run((new Image())->make(url('/images/marketing/app-screenshot.png'))),

                    ]

                ],


            ]
        );
    }
}
