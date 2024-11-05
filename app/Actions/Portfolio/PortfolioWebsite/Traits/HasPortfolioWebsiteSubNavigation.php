<?php

namespace App\Actions\Portfolio\PortfolioWebsite\Traits;

use Lorisleiva\Actions\ActionRequest;

trait HasPortfolioWebsiteSubNavigation {

    public function getSubNavigation(ActionRequest $request): array
    {
        $meta = [];


        $meta[] = [
            'href'     => [
                'name'       => 'customer.portfolio.websites.show',
                'parameters' => array_merge(
                    $request->route()->originalParameters(),
                    [
                        '_query' => [
                            'tab' => 'prospects'
                        ]
                    ]
                )
            ],

            'label'    => __('Website'),
            'leftIcon' => [
                'icon'    => 'fal fa-globe',
                'tooltip' => __('website')
            ]
        ];

        $meta[] = [
            'href'     => [
                'name'       => 'customer.seo.dashboard',
                'parameters' => array_merge(
                    $request->route()->originalParameters(),
                    [
                        '_query' => [
                            'tab' => 'prospects'
                        ]
                    ]
                )
            ],
            'number'   => 3,
            'label'    => __('Webpages'),
            'leftIcon' => [
                'icon'    => 'fal fa-browser',
                'tooltip' => __('webpages')
            ]
        ];

        $meta[] = [
            'href'     => [
                'name'       => 'customer.portfolio.websites.footer',
                'parameters' => array_merge(
                    $request->route()->originalParameters()
                )
            ],
            'label'    => __('Footer'),
            'leftIcon' => [
                'icon'    => 'fal fa-football-ball',
                'tooltip' => __('footer')
            ]
        ];

        return $meta;
    }
}
