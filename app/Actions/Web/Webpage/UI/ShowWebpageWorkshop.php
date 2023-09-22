<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowWebpageWorkshop extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('websites.edit');
        $this->canDelete = $request->user()->can('websites.edit');

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request);
        return $webpage;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(Website $website, Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request);
        return $webpage;
    }

    public function htmlResponse(Webpage $webpage, ActionRequest $request): Response
    {
        return Inertia::render(
            'Web/WebpageWorkshop',
            [
                'title'       => __("Webpage's workshop"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),
                'pageHead'    => [

                    'title' => $webpage->code,
                    'icon'  => [
                        'title' => __('webpage'),
                        'icon'  => 'fal fa-browser'
                    ],
                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Webpage's workshop")
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


            ]
        );
    }

    public function getBreadcrumbs(array $routeParameters): array
    {
        return ShowWebpage::make()->getBreadcrumbs(
            $routeParameters,
            suffix: '('.__('workshop').')'
        );
    }


}