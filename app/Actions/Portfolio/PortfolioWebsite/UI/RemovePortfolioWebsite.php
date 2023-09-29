<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class RemovePortfolioWebsite extends InertiaAction
{
    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("inventory.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request);

        return $this->handle($portfolioWebsite);
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

    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
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
                    'title'  => $portfolioWebsite->slug,
                    'actions'=> [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/remove$/', 'show', $request->route()->getName()),
                                'parameters' => $portfolioWebsite->slug
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
