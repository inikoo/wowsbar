<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Landlord\UI;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowWelcome
{
    use AsController;

    public function handle(): Response
    {
        return Inertia::render(
            'Landlord/Welcome',
            [

                'hero'=>[
                    'whatsNew' => [
                        'label' => __('Just shipped v0.9'),
                        'route' => [
                            'name'       => 'landlord.whats-new',
                            'parameters' => null
                        ]
                    ],
                    'title'=>'Your One-Stop Platform Banners Creator',
                    'text'=>"Create stunning website banners effortlessly! Easy to use, customizable templates, and no design experience required. Elevate your website's appeal today!"
                ],


            ]
        );
    }
}
