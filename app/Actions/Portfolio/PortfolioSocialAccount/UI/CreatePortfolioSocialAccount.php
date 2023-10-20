<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithPortfolioWebsiteFields;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreatePortfolioSocialAccount extends InertiaAction
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
                'title'       => __('new social account'),
                'pageHead'    => [
                    'title'   => __('social account'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]


                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('Account'),
                            'fields' => [
                                'platform' => [
                                    'type'          => 'select',
                                    'label'         => __('platform'),
                                    'placeholder'   => __('Select social platform'),
                                    'options'       => Options::forEnum(PortfolioSocialAccountPlatformEnum::class)->toArray(),
                                    'required'      => true,
                                    'mode'          => 'single'
                                ],
                                'username' => [
                                    'type'     => 'input',
                                    'label'    => __('username'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                                'url' => [
                                    'type'     => 'input',
                                    'label'    => __('profile url'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                            ]
                        ],

                    ],
                    'route' => [
                        'name' => 'customer.models.portfolio-social-account.store',
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
                        'label' => __("creating social account"),
                    ]
                ]
            ]
        );
    }


}
