<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:04 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Tags\UI;

use App\Actions\Helpers\Query\BuildQuery;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Leads\Prospect\UI\ShowProspect;
use App\Enums\UI\Organisation\ShowProspectTabsEnum;
use App\Http\Resources\CRM\ProspectsResource;
use App\Models\Helpers\Query;
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
        return $request->user()->hasPermissionTo('crm.view');
    }

    public function handle(Tag $tag): Tag
    {
        return $tag;
    }

    public function asController(Shop $shop, $tag, ActionRequest $request): Tag
    {
        $tag = Tag::findFromString($tag);
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
                    $request->route()->parameters
                ),
                'title'    => __($tag->name),
                'pageHead' => [
                    'title'     => __($tag->name),
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => ShowProspectTabsEnum::navigation()
                ],
                'tags' => $tag,
                ShowProspectTabsEnum::PROSPECTS->value => $this->tab == ShowProspectTabsEnum::PROSPECTS->value ?
                    fn () => ProspectsResource::collection(Prospect::withAnyTags($tag->name)->paginate())
                    : Inertia::lazy(fn () => ProspectsResource::collection(Prospect::withAnyTags($tag->name)->paginate())),
            ]
        )->table(IndexProspects::make()->tableStructure(parent: $this->parent, prefix: ShowProspectTabsEnum::PROSPECTS->value));
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('images'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.images.index' =>
            array_merge(
                ShowProspect::make()->getBreadcrumbs($routeName, $routeParameters),
                $headCrumb(
                    [
                        'name' => 'portfolio.images.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
