<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\SysAdmin\UI\CRM\ShowCRMDashboard;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\Traits\WithProspectsSubNavigation;
use App\Enums\UI\Organisation\ProspectsTabsEnum;
use App\Enums\UI\ProspectTabsEnum;
use App\Http\Resources\CRM\ProspectResource;
use App\Http\Resources\History\HistoryResource;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowProspect extends InertiaAction
{
    use WithProspectsSubNavigation;
    use WithActionButtons;

    public Organisation|Shop $parent;

    public function handle(Prospect $prospect): Prospect
    {
        return $prospect;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('crm.prospects.edit');
        $this->canDelete = $request->user()->hasPermissionTo('crm.prospects.edit');

        return $request->user()->hasPermissionTo("crm.prospects.view");
    }

    public function asController(Prospect $prospect, ActionRequest $request): Prospect
    {
        $this->parent = organisation();
        $this->initialisation($request)->withTab(ProspectTabsEnum::values());

        return $this->handle($prospect);
    }

    public function inShop(Shop $shop, Prospect $prospect, ActionRequest $request): Prospect
    {
        $this->initialisation($request)->withTab(ProspectTabsEnum::values());
        $this->parent = $shop;

        return $this->handle($prospect);
    }

    public function htmlResponse(Prospect $prospect, ActionRequest $request): Response
    {
        $subNavigation = $this->getSubNavigation($request);
        return Inertia::render(
            'CRM/Prospect',
            [
                'title'       => __('Prospect'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($prospect, $request),
                    'next'     => $this->getNext($prospect, $request),
                ],
                'pageHead'    => [
                    'title'         => $prospect->name ?? $prospect->email ?? $prospect->slug,
                    'subNavigation' => $subNavigation,
                    'noCapitalise'  => !$prospect->name,
                    'icon'          => [
                        'icon'  => ['fal', 'fa-transporter'],
                        'title' => __('Prospect')
                    ],
                    'iconRight'          => $prospect->state->stateIcon()[$prospect->state->value],
                    'actions'            => [
                        $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => ProspectTabsEnum::navigation()
                ],

                'unsubscribe' => [
                    'route' => [
                        'name'       => 'org.models.shop.prospect.unsubscribe.update',
                        'parameters' => [
                            'shop'     => $prospect->shop->id,
                            'prospect' => $prospect->id
                        ]
                    ]
                ],

                ProspectTabsEnum::SHOWCASE->value => $this->tab == ProspectTabsEnum::SHOWCASE->value ?
                    fn () => GetProspectShowcase::run($prospect)
                    : Inertia::lazy(fn () => GetProspectShowcase::run($prospect)),

                ProspectsTabsEnum::HISTORY->value => $this->tab == ProspectsTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(model: $prospect, prefix: ProspectTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(model: $prospect, prefix: ProspectTabsEnum::HISTORY->value))),


            ]
        )->table(IndexHistory::make()->tableStructure(prefix: ProspectTabsEnum::HISTORY->value));
    }

    public function jsonResponse(Prospect $prospect): ProspectResource
    {
        return new ProspectResource($prospect);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Prospect $prospect, array $routeParameters, string $suffix = null) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('Prospects')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $prospect->slug,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'org.crm.prospects.show',
            'org.crm.prospects.edit'
            => array_merge(
                ShowCRMDashboard::make()->getBreadcrumbs('org.crm.dashboard'),
                $headCrumb(
                    Prospect::where('slug', $routeParameters['prospect'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.prospects.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.crm.prospects.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.crm.shop.prospects.show',
            'org.crm.shop.prospects.edit'
            => array_merge(
                ShowCRMDashboard::make()->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    Prospect::where('slug', $routeParameters['prospect'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.prospects.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.prospects.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
        };
    }

    public function getPrevious(Prospect $prospect, ActionRequest $request): ?array
    {
        $previous = Prospect::where('slug', '<', $prospect->slug)->when(true, function ($query) use ($prospect, $request) {
            if ($request->route()->getName() == 'org.shops.show.prospects.show') {
                $query->where('Prospects.shop_id', $prospect->shop_id);
            }
        })->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Prospect $prospect, ActionRequest $request): ?array
    {
        $next = Prospect::where('slug', '>', $prospect->slug)->when(true, function ($query) use ($prospect, $request) {
            if ($request->route()->getName() == 'org.shops.show.prospects.show') {
                $query->where('Prospects.shop_id', $prospect->shop_id);
            }
        })->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Prospect $prospect, string $routeName): ?array
    {
        if (!$prospect) {
            return null;
        }

        return match ($routeName) {
            'org.crm.prospects.show' => [
                'label' => $prospect->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'prospect' => $prospect->slug
                    ]
                ]
            ],
            'org.crm.shop.prospects.show' => [
                'label' => $prospect->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'shop'     => $prospect->shop->slug,
                        'prospect' => $prospect->slug
                    ]
                ]
            ]
        };
    }
}
