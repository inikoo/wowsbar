<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Enums\UI\Organisation\WebsiteWorkshopTabsEnum;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowWebsiteWorkshop extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('websites.edit');
        $this->canDelete = $request->user()->hasPermissionTo('websites.edit');

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $this->initialisation($request)->withTab(WebsiteWorkshopTabsEnum::values());

        return $website;
    }


    public function htmlResponse(Website $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'Web/WebsiteWorkshop',
            [
                'title'       => __("Website's workshop"),
                'breadcrumbs' => $this->getBreadcrumbs($request->route()->originalParameters()),
                'pageHead'    => [

                    'title' => __('Workshop'),

                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Website's workshop")
                        ],

                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'icon'  => 'far fa-desktop',
                            'label' => __('Preview'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'preview', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => WebsiteWorkshopTabsEnum::navigation(),
                ],
                'structure'         => $website->compiled_structure,
                'imagesUploadRoute' => [
                    'name'       => 'org.models.website.images.store',
                    'parameters' => $website->id
                ],
                'updateRoutes' => [
                    'workshop_header'=> [
                        'name'       => 'org.models.website.header.update',
                        'parameters' => $website->id
                    ],
                    'workshop_footer'=> [
                        'name'       => 'org.models.website.footer.update',
                        'parameters' => $website->id
                    ],
                    'workshop_layout'=> [
                        'name'       => 'org.models.website.layout.update',
                        'parameters' => $website->id
                    ],
                ],

                WebsiteWorkshopTabsEnum::LAYOUT->value => $this->tab == WebsiteWorkshopTabsEnum::LAYOUT->value
                    ?
                    fn () => GetWebsiteWorkshopLayout::run($website)
                    : Inertia::lazy(
                        fn () => GetWebsiteWorkshopLayout::run($website)
                    ),

                WebsiteWorkshopTabsEnum::HEADER->value => $this->tab == WebsiteWorkshopTabsEnum::HEADER->value
                    ?
                    fn () => GetWebsiteWorkshopHeader::run($website)
                    : Inertia::lazy(
                        fn () => GetWebsiteWorkshopHeader::run($website)
                    ),

                WebsiteWorkshopTabsEnum::FOOTER->value => $this->tab == WebsiteWorkshopTabsEnum::FOOTER->value ?
                    fn () => GetWebsiteWorkshopFooter::run($website)
                    : Inertia::lazy(fn () => GetWebsiteWorkshopFooter::run($website)),


            ]
        );
    }

    public function getBreadcrumbs(array $routeParameters): array
    {
        return ShowWebsite::make()->getBreadcrumbs(
            $routeParameters,
            suffix: '('.__('editing').')'
        );
    }


}
