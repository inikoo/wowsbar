<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\UI;

use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
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
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Tenant|Website $parent, $prefix = null, WebBlockType $webBlockType = null): LengthAwarePaginator
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
        Tenant|Website $parent,
        ?array $modelOperations = null,
        $prefix = null,
        WebBlockType $webBlockType = null,
        $canEdit = false
    ): Closure {
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
                $emptyState = match ($webBlockType->slug) {
                    WebBlockTypeSlugEnum::BANNER => [
                        'title'  => __('No banners found'),
                        'count'  => app('currentTenant')->stats->number_content_blocks_web_block_type_banner,
                        'action' => $canEdit && class_basename($parent) == 'Website' ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new banner'),
                            'label'   => __('banner'),
                            'route'   => [
                                'name'       => 'portfolio.websites.show.banners.create',
                                'parameters' => ['website' => $parent->slug]
                            ]
                        ] : null
                    ],
                    default => null
                };
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
