<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 18:46:22 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Enums\UI\Organisation\WebsiteWorkshopTabsEnum;
use App\Models\Organisation\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowWebsiteWorkshopPreview extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('website.edit');
        $this->canDelete = $request->user()->can('website.edit');

        return $request->user()->hasPermissionTo("website.edit");
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $this->initialisation($request)->withTab(WebsiteWorkshopTabsEnum::values());

        return $website;
    }


    public function htmlResponse(Website $website, ActionRequest $request): Response
    {

        return Inertia::render(
            'Web/PreviewWorkshop',
            [
                'title'       => __("Website's workshop"),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'pageHead'    => [

                    'title'    => __('Workshop'),

                    'iconRight'    =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Website's workshop")
                        ],

                    'actions' => [
                        [
                            'type'       => 'button',
                            'style'      => 'exitEdit',
                            'label'      => __('Exit workshop'),
                            'route'      => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                        [
                            'type'       => 'button',
                            'style'      => 'exitEdit',
                            'label'      => __('Preview'),
                            'route'      => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ]
                    ],
                ],
            ]
        );
    }

    public function getBreadcrumbs(): array
    {
        return ShowWebsite::make()->getBreadcrumbs(
            suffix: '('.__('editing').')'
        );
    }


}
