<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 17:42:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use Lorisleiva\Actions\ActionRequest;

trait WithCustomersSubNavigation
{
    public function getSubNavigation(ActionRequest $request): array
    {
        $meta = [];

        $meta[] = [
            'href'     => [
                'name'       => 'org.crm.shop.customers.index',
                'parameters' => array_merge(
                    $request->route()->originalParameters(),
                    [
                        '_query' => [
                            'tab' => 'customers'
                        ]
                    ]
                )
            ],
            'number'   => $this->parent->crmStats->number_customers,
            'label'    => __('Customers'),
            'leftIcon' => [
                'icon'    => 'fal fa-user',
                'tooltip' => __('customers')
            ]
        ];

        if ($this->parent->crmStats->number_customers > 0) {

            $meta[] = [
                'href'     => [
                    'name'       => 'org.crm.shop.customers.newsletters.index',
                    'parameters' => $request->route()->originalParameters()
                ],
                'number'   => $this->parent->mailStats->number_mailshots_type_newsletter,
                'label'    => __('Newsletters'),
                'leftIcon' => [
                    'icon'    => 'fal fa-newspaper',
                    'tooltip' => __('newsletters')
                ]
            ];

            $meta[] = [
                'href'     => [
                    'name'       => 'org.crm.shop.customers.mailshots.index',
                    'parameters' => $request->route()->originalParameters()
                ],
                'number'   => $this->parent->mailStats->number_mailshots_type_marketing,
                'label'    => __('Mailshots'),
                'leftIcon' => [
                    'icon'    => 'fal fa-mail-bulk',
                    'tooltip' => __('mailshots')
                ]
            ];
        }

        $meta[] = [
            'href'     => [
                'name'       => 'org.crm.shop.customers.surveys.index',
                'parameters' => $request->route()->originalParameters()
            ],
            'number'   => $this->parent->crmStats->number_surveys,
            'label'    => __('Surveys'),
            'leftIcon' => [
                'icon'    => 'fal fa-poll-people',
                'tooltip' => __('surveys')
            ]
        ];

        $meta[] = [
            'href'     => [
                'name'       => 'org.crm.shop.customers.lists.index',
                'parameters' => $request->route()->originalParameters()
            ],
            'number'   => $this->parent->crmStats->number_customer_queries,
            'label'    => __('Lists'),
            'leftIcon' => [
                'icon'    => 'fal fa-code-branch',
                'tooltip' => __('lists')
            ]
        ];

        $meta[] = [
            'href'     => [
                'name'       => 'org.crm.shop.customers.tags.index',
                'parameters' => $request->route()->originalParameters()
            ],
            'number'   => organisation()->crmStats->number_tags,
            'label'    => __('Tags'),
            'leftIcon' => [
                'icon'    => 'fal fa-tags',
                'tooltip' => __('tags')
            ]
        ];



        return $meta;
    }
}
