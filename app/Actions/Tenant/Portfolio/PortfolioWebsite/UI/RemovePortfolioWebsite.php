<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class RemovePortfolioWebsite extends InertiaAction
{
    public function handle(PortfolioWebsite $website): PortfolioWebsite
    {
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("inventory.edit");
    }

    public function asController(PortfolioWebsite $website, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request);

        return $this->handle($website);
    }


    public function getAction($route): array
    {
        return  [
            'buttonLabel' => __('Delete'),
            'title'       => __('Delete PortfolioWebsite'),
            'text'        => __("This action will delete this PortfolioWebsite and its dependent"),
            'route'       => $route
        ];
    }

    public function htmlResponse(PortfolioWebsite $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'RemoveModel',
            [
                'title'       => __('delete employee'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'icon'  =>
                        [
                            'icon'  => ['fal', 'fa-inventory'],
                            'title' => __('employee')
                        ],
                    'title'  => $website->slug,
                    'actions'=> [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/remove$/', 'show', $request->route()->getName()),
                                'parameters' => $website->slug
                            ]
                        ]
                    ]
                ],
                'data'      => $this->getAction(
                    route:[
                        'name'       => 'models.portfolio-website.delete',
                        'parameters' => $request->route()->originalParameters()
                    ]
                )
            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowPortfolioWebsite::make()->getBreadcrumbs($routeName, $routeParameters, suffix: '('.__('deleting').')');
    }
}
