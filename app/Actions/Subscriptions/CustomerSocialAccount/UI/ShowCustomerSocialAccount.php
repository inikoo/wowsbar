<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerSocialAccount\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountAds\UI\IndexPortfolioSocialAccountAds;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\UI\IndexPortfolioSocialAccountPosts;
use App\Actions\Subscriptions\CustomerSocialAccount\CustomerSocialAccountPost\UI\IndexCustomerSocialAccountPosts;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Customer\PortfolioSocialAccountTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountPostsResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerSocialAccount;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCustomerSocialAccount extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo("crm.edit");
        $this->canDelete = $request->user()->hasPermissionTo("crm.edit");

        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function asController(CustomerSocialAccount $customerSocialAccount, ActionRequest $request): CustomerSocialAccount
    {
        $this->initialisation($request)->withTab(PortfolioSocialAccountTabsEnum::values());

        return $customerSocialAccount;
    }

    public function inCustomer(Shop $shop, Customer $customer, CustomerSocialAccount $customerSocialAccount, ActionRequest $request): CustomerSocialAccount
    {
        $this->initialisation($request)->withTab(PortfolioSocialAccountTabsEnum::values());

        return $customerSocialAccount;
    }

    public function htmlResponse(CustomerSocialAccount $customerSocialAccount, ActionRequest $request): Response
    {
        $customer = $request->get('customer');
        return Inertia::render(
            'Subscriptions/PortfolioSocialAccount',
            [
                'title'       => __('Customer Social Account'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($customerSocialAccount, $request),
                    'next'     => $this->getNext($customerSocialAccount, $request),
                ],
                'pageHead' => [
                    'title' => $customerSocialAccount->username,
                    'icon'  => [
                        'title' => __('customer social account'),
                        'icon'  => $customerSocialAccount->platform->platformIcon()[$customerSocialAccount->platform->value]['icon']
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioSocialAccountTabsEnum::navigation()
                ],

                PortfolioSocialAccountTabsEnum::POST->value => $this->tab == PortfolioSocialAccountTabsEnum::POST->value ?
                    fn () => PortfolioSocialAccountPostsResource::collection(IndexCustomerSocialAccountPosts::run($customerSocialAccount, tab: $this->tab))
                    : Inertia::lazy(fn () => PortfolioSocialAccountPostsResource::collection(IndexCustomerSocialAccountPosts::run($customerSocialAccount, tab: $this->tab))),

                PortfolioSocialAccountTabsEnum::ADS->value => $this->tab == PortfolioSocialAccountTabsEnum::ADS->value ?
                    fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($customerSocialAccount, tab: $this->tab))
                    : Inertia::lazy(fn () => PortfolioSocialAccountPostsResource::collection(IndexPortfolioSocialAccountPosts::run($customerSocialAccount, tab: $this->tab))),

                PortfolioSocialAccountTabsEnum::CHANGELOG->value => $this->tab == PortfolioSocialAccountTabsEnum::CHANGELOG->value ?
                    fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run(
                        customer: $customer,
                        model: $customerSocialAccount,
                        prefix: PortfolioSocialAccountTabsEnum::CHANGELOG->value
                    ))
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run(
                        customer: $customer,
                        model: $customerSocialAccount,
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

    public function jsonResponse(CustomerSocialAccount $customerSocialAccount): PortfolioSocialAccountResource
    {
        return new PortfolioSocialAccountResource($customerSocialAccount);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, CustomerSocialAccount $customerSocialAccount, array $routeParameters, string $suffix) {
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
                            'label' => $customerSocialAccount->username,
                        ],

                    ],
                    'simple' => [
                        'route' => $routeParameters['model'],
                        'label' => $customerSocialAccount->username
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
                    CustomerSocialAccount::firstWhere('slug', $routeParameters['customerSocialAccount']),
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

    public function getPrevious(CustomerSocialAccount $customerSocialAccount, ActionRequest $request): ?array
    {
        $previous = CustomerSocialAccount::where('slug', '<', $customerSocialAccount->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(CustomerSocialAccount $customerSocialAccount, ActionRequest $request): ?array
    {
        $next = CustomerSocialAccount::where('slug', '>', $customerSocialAccount->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?CustomerSocialAccount $customerSocialAccount, string $routeName): ?array
    {
        if (!$customerSocialAccount) {
            return null;
        }

        return match ($routeName) {
            'org.crm.shop.customers.show.customer-social-accounts.show',
            'customer.portfolio.social-accounts.edit' => [
                'label' => $customerSocialAccount->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioSocialAccount' => $customerSocialAccount->slug
                    ]
                ]
            ]
        };
    }
}
