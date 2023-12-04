<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 15:38:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Surveys\UI;

use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\WithCustomersSubNavigation;
use App\Enums\UI\Organisation\SurveysTabsEnum;
use App\Http\Resources\CRM\SurveysResource;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Tag\CrmTagResource;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Survey;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class IndexCustomerSurveys extends InertiaAction
{
    use WithCustomersSubNavigation;

    private Shop|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('crm.prospects.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(SurveysTabsEnum::values());
        $this->parent = organisation();

        return $this->handle(prefix: SurveysTabsEnum::SURVEYS->value);
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(SurveysTabsEnum::values());
        $this->parent = $shop;

        return $this->handle(prefix: SurveysTabsEnum::SURVEYS->value);
    }

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereWith('tags.label', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Survey::class);

        /** @noinspection PhpUndefinedMethodInspection */
        return $query
            ->allowedSorts(['name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix . 'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('You dont have any surveys'),
                        'description' => null,
                        'count' => 0
                    ]
                )
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true);
        };
    }


    public function htmlResponse(LengthAwarePaginator $tags, ActionRequest $request): Response
    {
        $subNavigation = $this->getSubNavigation($request);

        return Inertia::render(
            'CRM/Customers/Surveys',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters(),
                ),
                'title' => __('customer surveys'),
                'pageHead' => [
                    'title' => __('surveys'),
                    'subNavigation' => $subNavigation,
                    'actions' => [
                        $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new surveys'),
                            'label'   => __('surveys'),
                            'route'   => [
                                'name'       => 'org.crm.shop.customers.surveys.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : []
                    ],
                ],

                'tabs' => [
                    'current' => $this->tab,
                    'navigation' => SurveysTabsEnum::navigation(),
                ],

                SurveysTabsEnum::SURVEYS->value => $this->tab == SurveysTabsEnum::SURVEYS->value ?
                    fn() => SurveysResource::collection($tags)
                    : Inertia::lazy(fn() => SurveysResource::collection($tags)),

                SurveysTabsEnum::HISTORY->value => $this->tab == SurveysTabsEnum::HISTORY->value ?
                    fn() => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: SurveysTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: SurveysTabsEnum::HISTORY->value))),


            ]
        )->table($this->tableStructure(prefix: SurveysTabsEnum::SURVEYS->value))
            ->table(IndexHistory::make()->tableStructure(prefix: SurveysTabsEnum::HISTORY->value));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        return match ($routeName) {
            'org.crm.shop.customers.surveys.index' =>
            array_merge(
                (new IndexCustomers())->getBreadcrumbs(
                    'org.crm.customers.index',
                    $routeParameters
                ),
                [
                    [
                        'type' => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'org.crm.shop.customers.surveys.index',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('surveys'),
                            'icon' => 'fal fa-bars',
                        ],
                        'suffix' => $suffix

                    ]
                ]
            ),
            default => []
        };
    }


}
