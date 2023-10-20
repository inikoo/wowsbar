<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountAds\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\IndexPortfolioSocialAccounts;
use App\Actions\Traits\Fields\WithPortfolioWebsiteFields;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreatePortfolioSocialAccountAds extends InertiaAction
{
    use WithPortfolioWebsiteFields;

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo('portfolio.edit');
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request);
        return $request;

    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $request->route()->getName();

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new ads'),
                'pageHead'    => [
                    'title'   => __('ads'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'customer.portfolio.social-accounts.show',
                                'parameters' => array_merge($request->route()->originalParameters(), ['tab' => PortfolioSocialAccountPostTypeEnum::ADS->value])
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('Ads'),
                            'fields' => [
                                'task_name' => [
                                    'type'     => 'input',
                                    'label'    => __('task name'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                                'start_at' => [
                                    'type'     => 'date',
                                    'label'    => __('start at'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                                'end_at' => [
                                    'type'     => 'date',
                                    'label'    => __('end at'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                                'description' => [
                                    'type'     => 'textarea',
                                    'label'    => __('description'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                                'notes' => [
                                    'type'     => 'textarea',
                                    'label'    => __('notes'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                            ]
                        ],

                    ],
                    'route' => [
                        'name' => 'customer.models.portfolio-social-account.ads.store',
                        'parameters' => $this->originalParameters
                    ],
                ],
            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexPortfolioSocialAccounts::make()->getBreadcrumbs(
                'customer.portfolio.social-accounts.index',
                []
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating ads"),
                    ]
                ]
            ]
        );
    }


}
