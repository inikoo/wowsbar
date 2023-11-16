<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:04 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Tags\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Enums\UI\Organisation\ShowProspectTabsEnum;
use App\Http\Resources\CRM\ProspectsResource;
use App\Models\Helpers\Tag;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowProspectTag extends InertiaAction
{
    public Organisation|Shop $parent;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.prospects.view');
    }

    public function handle(Tag $tag): Tag
    {
        return $tag;
    }

    public function inShop(Shop $shop, Tag $tag, ActionRequest $request): Tag
    {

        $this->parent = $shop;
        $this->initialisation($request)->withTab(ShowProspectTabsEnum::values());

        return $this->handle($tag);
    }

    public function htmlResponse(Tag $tag, ActionRequest $request): Response
    {
        return Inertia::render(
            'Prospects/ProspectTag',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'    => __($tag->name),
                'pageHead' => [
                    'title'     => __($tag->name),
                    'icon'      => [
                        'tooltip' => __('tag'),
                        'icon'    => 'fal fa-tags'
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => ShowProspectTabsEnum::navigation()
                ],
                'tags'                                 => $tag,
                ShowProspectTabsEnum::PROSPECTS->value => $this->tab == ShowProspectTabsEnum::PROSPECTS->value ?
                    fn () => ProspectsResource::collection(Prospect::withAnyTagsOfAnyType($tag->tag_slug)->paginate())
                    : Inertia::lazy(fn () => ProspectsResource::collection(Prospect::withAnyTagsOfAnyType($tag->tag_slug)->paginate())),
            ]
        )->table(IndexProspects::make()->tableStructure(parent: $this->parent, prefix: ShowProspectTabsEnum::PROSPECTS->value));
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = null): array
    {
        $headCrumb = function (string $type, Tag $tag, array $routeParameters, string $suffix = null) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('tags')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $tag->label,
                        ],

                    ],
                    'simple' => [
                        'route' => $routeParameters['model'],
                        'label' => $tag->label
                    ],
                    'suffix' => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.shop.prospects.tags.show',
            'org.crm.shop.prospects.tags.edit' =>
            array_merge(
                IndexProspects::make()->getBreadcrumbs(
                    'org.crm.shop.prospects.index',
                    $routeParameters
                ),
                $headCrumb(
                    'modelWithIndex',
                    Tag::firstWhere('tag_slug', $routeParameters['tag']),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.prospects.tags.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.prospects.tags.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            default => []
        };
    }
}
