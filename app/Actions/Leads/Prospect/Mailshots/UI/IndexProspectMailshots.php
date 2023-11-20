<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 15:38:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Mailshots\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\WithProspectsMeta;
use App\Enums\Mail\MailshotTypeEnum;
use App\Enums\UI\Organisation\ProspectsMailshotsTabsEnum;
use App\Http\Resources\Mail\MailshotsResource;
use App\InertiaTable\InertiaTable;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProspectMailshots extends InertiaAction
{
    use WithProspectsMeta;

    private Shop|Organisation $parent;

    protected function getElementGroups(): array
    {
        return [];
    }

    public function handle(Organisation|Shop $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhere('mailshots.slug', 'ILIKE', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Mailshot::class)
            ->leftJoin('mailshot_stats', 'mailshot_stats.mailshot_id', 'mailshots.id')
            ->where('type', MailshotTypeEnum::PROSPECT_MAILSHOT);

        if (class_basename($parent) == 'Shop') {
            $queryBuilder->where('scope_id', $parent->id);
        }

        foreach ($this->getElementGroups() as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('mailshots.slug')
            ->allowedSorts(['slug'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix = null): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->getElementGroups() as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }

            $table
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title'       => __('no mailshots'),
                        'description' => $this->canEdit ? __('Get started by creating a new mailshots.') : null,
                        'count'       => 0,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new mailshot'),
                            'label'   => __('mailshot'),
                            'route'   => [
                                'name'       => 'org.crm.shop.prospects.mailshots.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'], type: 'icon')
                ->column(key: 'subject', label: __('subject'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'number_recipients', label: __('recipients'), sortable: true)
                ->column(key: 'actions', label: ' ')
                ->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return
            (
                $request->user()->hasPermissionTo('crm.prospects.view')
            );
    }

    public function htmlResponse(LengthAwarePaginator $mailshots, ActionRequest $request): Response
    {
        $meta = $this->getMeta($request);

        return Inertia::render(
            'CRM/Prospects/Mailshots',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('prospects mailshots'),
                'pageHead'    => [
                    'title'   => __('prospects mailshots'),
                    'meta'    => $meta,
                    'actions' =>
                        [
                            [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => __('New mailshot'),
                                'route' => [
                                    'name'       => 'org.crm.shop.prospects.mailshots.create',
                                    'parameters' => array_values($this->originalParameters)
                                ]
                            ]
                        ]


                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ProspectsMailshotsTabsEnum::navigation(),
                ],

                ProspectsMailshotsTabsEnum::MAILSHOTS->value => $this->tab == ProspectsMailshotsTabsEnum::MAILSHOTS->value ?
                    fn () => MailshotsResource::collection($mailshots)
                    : Inertia::lazy(fn () => MailshotsResource::collection($mailshots)),


            ]
        )->table($this->tableStructure(prefix: ProspectsMailshotsTabsEnum::MAILSHOTS->value));
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ProspectsMailshotsTabsEnum::values());

        return $this->handle(organisation());
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ProspectsMailshotsTabsEnum::values());

        return $this->handle($shop);
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        return match ($routeName) {
            'org.crm.shop.prospects.mailshots.index' =>
            array_merge(
                (new IndexProspects())->getBreadcrumbs(
                    'org.crm.shop.prospects.index',
                    $routeParameters
                ),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name'       => 'org.crm.shop.prospects.mailshots.index',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('mailshots'),
                            'icon'  => 'fal fa-bars',
                        ],
                        'suffix' => $suffix

                    ]
                ]
            ),
            default => []
        };
    }


}
