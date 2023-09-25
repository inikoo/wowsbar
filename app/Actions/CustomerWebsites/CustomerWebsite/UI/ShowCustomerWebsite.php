<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CustomerWebsites\CustomerWebsite\UI;


use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\CustomerWebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\CustomerWebsites\CustomerWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCustomerWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return !$request->user()->can('portfolio.view');
    }

    public function asController(CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request)->withTab(CustomerWebsiteTabsEnum::values());

        return $customerWebsite;
    }

    public function htmlResponse(CustomerWebsite $customerWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'CustomerWebsites/CustomerWebsite',
            [
                'title'       => __('CustomerWebsite'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($customerWebsite, $request),
                    'next'     => $this->getNext($customerWebsite, $request),
                ],
                'pageHead'    => [
                    'title'   => $customerWebsite->name,
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerWebsiteTabsEnum::navigation()
                ],

                CustomerWebsiteTabsEnum::CHANGELOG->value => $this->tab == CustomerWebsiteTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($customerWebsite))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($customerWebsite))),
/*
                CustomerWebsiteTabsEnum::BANNERS->value => $this->tab == CustomerWebsiteTabsEnum::BANNERS->value ?
                    fn () => BannerResource::collection(IndexBanners::run($customerWebsite))
                    : Inertia::lazy(fn () => BannerResource::collection(IndexBanners::run($customerWebsite)))
*/
            ]
        )->table(IndexHistories::make()->tableStructure());
    }

    public function jsonResponse(CustomerWebsite $customerWebsite): CustomerWebsiteResource
    {
        return new CustomerWebsiteResource($customerWebsite);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, CustomerWebsite $customerWebsite, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('customer websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $customerWebsite->name,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $customerWebsite->name
                    ],
                    'suffix' => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'org.customer-websites.show',
            'org.customer-websites.edit' =>
            array_merge(
                IndexCustomerWebsites::make()->getBreadcrumbs(
                    'org.customer-websites.index',
                    []
                ),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['customerWebsite'],
                    [
                        'index' => [
                            'name'       => 'org.customer-websites.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.customer-websites.show',
                            'parameters' => [$routeParameters['customerWebsite']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $previous = CustomerWebsite::where('code', '<', $customerWebsite->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $next = CustomerWebsite::where('code', '>', $customerWebsite->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?CustomerWebsite $customerWebsite, string $routeName): ?array
    {
        if (!$customerWebsite) {
            return null;
        }

        return match ($routeName) {
            'org.customer-websites.show' => [
                'label' => $customerWebsite->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'customerWebsite' => $customerWebsite->slug
                    ]
                ]
            ]
        };
    }
}
