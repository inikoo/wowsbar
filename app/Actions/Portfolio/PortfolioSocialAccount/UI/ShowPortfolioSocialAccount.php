<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountAds\UI\IndexPortfolioSocialAccountAds;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\UI\IndexPortfolioSocialAccountPosts;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Customer\PortfolioSocialAccountTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountPostsResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\Models\Portfolio\PortfolioSocialAccount;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPortfolioSocialAccount extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        $this->canDelete = $request->get('customerUser')->hasPermissionTo('portfolio.edit');

        return $request->get('customerUser')->hasPermissionTo('portfolio.view');
    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): PortfolioSocialAccount
    {
        $this->initialisation($request)->withTab(PortfolioSocialAccountTabsEnum::values());

        return $portfolioSocialAccount;
    }

    public function htmlResponse(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): Response
    {
        $customer = $request->get('customer');
        return Inertia::render(
            'Portfolio/PortfolioSocialAccount',
            [
                'title'       => __('Portfolio Social Account'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($portfolioSocialAccount, $request),
                    'next'     => $this->getNext($portfolioSocialAccount, $request),
                ],
                'pageHead' => [
                    'title' => $portfolioSocialAccount->username,
                    'icon'  => [
                        'title' => __('portfolio social account'),
                        'icon'  => $portfolioSocialAccount->platform->platformIcon()[$portfolioSocialAccount->platform->value]['icon']
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => 'Edit Social Account',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : []
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioSocialAccountTabsEnum::navigation()
                ],

                PortfolioSocialAccountTabsEnum::POST->value => $this->tab == PortfolioSocialAccountTabsEnum::POST->value ?
                    fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($portfolioSocialAccount, tab: $this->tab))
                    : Inertia::lazy(fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($portfolioSocialAccount, tab: $this->tab))),

                PortfolioSocialAccountTabsEnum::ADS->value => $this->tab == PortfolioSocialAccountTabsEnum::ADS->value ?
                    fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($portfolioSocialAccount, tab: $this->tab))
                    : Inertia::lazy(fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($portfolioSocialAccount, tab: $this->tab))),

                PortfolioSocialAccountTabsEnum::CHANGELOG->value => $this->tab == PortfolioSocialAccountTabsEnum::CHANGELOG->value ?
                    fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run(
                        customer: $customer,
                        model: $portfolioSocialAccount,
                        prefix: PortfolioSocialAccountTabsEnum::CHANGELOG->value
                    ))
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run(
                        customer: $customer,
                        model: $portfolioSocialAccount,
                        prefix: PortfolioSocialAccountTabsEnum::CHANGELOG->value
                    ))),
            ]
        )
            ->table(IndexPortfolioSocialAccountPosts::make()->tableStructure(modelOperations: [
                'createLink' => [
                    [
                        'route' => [
                            'name'       => 'customer.portfolio.social-accounts.post.create',
                            'parameters' => array_values($this->originalParameters)
                        ],
                        'label' => __('post')
                    ]
                ],
            ], prefix: 'post'))
            ->table(IndexPortfolioSocialAccountAds::make()->tableStructure(modelOperations: [
                'createLink' => [
                    [
                        'route' => [
                            'name'       => 'customer.portfolio.social-accounts.ads.create',
                            'parameters' => array_values($this->originalParameters)
                        ],
                        'label' => __('ads')
                    ]
                ],
            ], prefix: 'ads'))
            ->table(IndexCustomerHistory::make()->tableStructure(prefix: PortfolioSocialAccountTabsEnum::CHANGELOG->value));
    }

    public function jsonResponse(PortfolioSocialAccount $portfolioSocialAccount): PortfolioSocialAccountResource
    {
        return new PortfolioSocialAccountResource($portfolioSocialAccount);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, PortfolioSocialAccount $portfolioSocialAccount, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $portfolioSocialAccount->username,
                        ],

                    ],
                    'simple' => [
                        'route' => $routeParameters['model'],
                        'label' => $portfolioSocialAccount->username
                    ],
                    'suffix' => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.social-accounts.show',
            'customer.portfolio.social-accounts.edit' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    PortfolioSocialAccount::firstWhere('slug', $routeParameters['portfolioSocialAccount']),
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.social-accounts.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.social-accounts.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): ?array
    {
        $previous = PortfolioSocialAccount::where('slug', '<', $portfolioSocialAccount->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): ?array
    {
        $next = PortfolioSocialAccount::where('slug', '>', $portfolioSocialAccount->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioSocialAccount $portfolioSocialAccount, string $routeName): ?array
    {
        if (!$portfolioSocialAccount) {
            return null;
        }


        return match ($routeName) {
            'customer.portfolio.social-accounts.show',
            'customer.portfolio.social-accounts.edit' => [
                'label' => $portfolioSocialAccount->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioSocialAccount' => $portfolioSocialAccount->slug
                    ]
                ]
            ]
        };
    }
}
