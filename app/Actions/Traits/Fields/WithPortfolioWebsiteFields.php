<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 23:08:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits\Fields;

trait WithPortfolioWebsiteFields
{
    protected function getPortfolioWebsiteFields(): array
    {
        return  [
            [
                'title'  => __('domain'),
                'fields' => [
                    'domain' => [
                        'type'      => 'inputWithAddOn',
                        'label'     => __('domain'),
                        'leftAddOn' => [
                            'label' => 'https://'
                        ],
                        'required'  => true,
                    ],
                ]
            ],
            [
                'title'  => __('ID/name'),
                'fields' => [

                    'code' => [
                        'type'      => 'input',
                        'label'     => __('code'),
                        'required'  => true,
                        'maxLength' => 8
                    ],
                    'name' => [
                        'type'      => 'input',
                        'label'     => __('name'),
                        'required'  => true,
                        'value'     => '',
                    ],


                ]
            ],

        ];
    }
}
