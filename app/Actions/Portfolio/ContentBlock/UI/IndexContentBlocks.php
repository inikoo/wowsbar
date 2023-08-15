<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\UI;

use App\Enums\Portfolio\ContentBlock\ContentBlockStateEnum;
use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlockType;
use Closure;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexContentBlocks
{
    use AsAction;

    protected array $elementGroups = [];


    protected function getElementGroups(): void
    {
        $this->elementGroups =
            [
                'state' => [
                    'label'    => __('State'),
                    'elements' => [
                        ContentBlockStateEnum::IN_PROCESS->value => [
                            __('In Process'),
                            app('currentTenant')->contentBlockStats->number_banners_in_process
                        ],
                        ContentBlockStateEnum::READY->value => [
                            __('Ready'),
                            app('currentTenant')->contentBlockStats->number_banners_ready
                        ],
                        ContentBlockStateEnum::LIVE->value => [
                            __('Live'),
                            app('currentTenant')->contentBlockStats->number_banners_live
                        ],
                        ContentBlockStateEnum::RETIRED->value => [
                            __('Retired'),
                            app('currentTenant')->contentBlockStats->number_banners_retired
                        ]
                    ],
                    'engine' => function ($query, $elements) {
                        $query->where('state', array_pop($elements) === 'number_banners_in_process');
                    }
                ]
            ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null, WebBlockType $webBlockType = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('content_blocks.name', $value)
                    ->orWhere('content_blocks.code', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(ContentBlock::class);

        $queryBuilder->when($webBlockType, function (Builder $query, WebBlockType $webBlockType) {
            $query->where('web_block_type_id', $webBlockType->id);
        });

        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('content_blocks.code')
            ->select(['content_blocks.code', 'content_blocks.name', 'content_blocks.slug'])
            ->allowedSorts(['slug', 'code', 'name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(
        Tenant|PortfolioWebsite $parent,
        ?array $modelOperations = null,
        $prefix = null,
        WebBlockType $webBlockType = null,
        $canEdit = false
    ): Closure {
        $this->getElementGroups();

        return function (InertiaTable $table) use ($modelOperations, $parent, $prefix, $webBlockType, $canEdit) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->elementGroups as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }


            $emptyState = [
                'title' => __('No content blocks found'),
                'count' => app('currentTenant')->stats->number_content_blocks,
            ];
            if ($webBlockType) {
                $emptyState = null;

                if ($webBlockType->slug == WebBlockTypeSlugEnum::BANNER) {
                    $action = null;

                    $description = null;
                    if ($canEdit) {
                        if (app('currentTenant')->stats->number_websites == 0) {
                            $description = __('Before creating your first banner you need a website').' 😉';

                            $action = [
                                'type'    => 'button',
                                'style'   => 'primary',
                                'tooltip' => __('new website'),
                                'label'   => __('website'),
                                'route'   => [
                                    'name' => 'portfolio.websites.create',
                                ]
                            ];
                        }
                    }

                    $emptyState = [
                        'title'       => __('No banners found'),
                        'count'       => app('currentTenant')->stats->number_content_blocks_web_block_type_banner,
                        'description' => $description,
                        'action'      => $action
                        /*
                        'action' => $canEdit && class_basename($parent) == 'PortfolioWebsite' ? [
                            'type'    => 'button',
                            'style'   => 'primary',
                            'tooltip' => __('new banner'),
                            'label'   => __('banner'),
                            'route'   => [
                                'name'       => 'portfolio.websites.show.banners.create',
                                'parameters' => ['website' => $parent->slug]
                            ]
                        ] : null
                        */
                    ];
                }
            }


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState($emptyState)
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'banner', label: __('banner'), sortable: true)
                ->defaultSort('slug');
        };
    }
}
