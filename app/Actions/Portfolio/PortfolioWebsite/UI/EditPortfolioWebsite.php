<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditPortfolioWebsite extends InertiaAction
{
    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");

    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request);
        return $this->handle($portfolioWebsite);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                    'title'       => __("PortfolioWebsite's settings"),
                    'breadcrumbs' => $this->getBreadcrumbs(
                        $request->route()->getName(),
                        $request->route()->originalParameters()
                    ),
                    'navigation'   => [
                        'previous' => $this->getPrevious($portfolioWebsite, $request),
                        'next'     => $this->getNext($portfolioWebsite, $request),
                    ],
                    'pageHead'    => [
                        'title'   => $portfolioWebsite->name,
                        'icon'    => [
                            'title' => __('website'),
                            'icon'  => 'fal fa-globe'
                        ],
                        'iconRight'    =>
                            [
                                'icon'  => ['fal', 'fa-edit'],
                                'title' => __("Editing website")
                            ],

                        'actions'   => [
                            [
                                'type'  => 'button',
                                'style' => 'exit',
                                'label' => __('Exit edit'),
                                'route' => [
                                    'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                    'parameters' => array_values($request->route()->originalParameters())
                                ]
                            ]
                        ],
                    ],
                    'formData' => [
                        'blueprint' => [
                            [
                                'title'  => __('ID/URL'),
                                'icon'   => 'fa-light fa-id-card',
                                'fields' => [
                                    'name' => [
                                        'type'     => 'input',
                                        'label'    => __('name'),
                                        'value'    => $portfolioWebsite->name,
                                        'required' => true,
                                    ],
                                    'url' => [
                                        'type'      => 'inputWithAddOn',
                                        'leftAddOn' => [
                                            'label' => 'https://'
                                        ],
                                        'label'     => __('url'),
                                        'value'=>preg_replace('/^https?:\/\//', '', $portfolioWebsite->url),
                                        'required'  => true,
                                    ],
                                ]
                            ],

                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'customer.models.portfolio-website.update',
                                'parameters' => $portfolioWebsite->id
                            ],
                        ]
                    ],

                ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowPortfolioWebsite::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '('.__('editing').')'
        );
    }

    public function getPrevious(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $previous = PortfolioWebsite::where('slug', '<', $portfolioWebsite->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $next = PortfolioWebsite::where('slug', '>', $portfolioWebsite->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioWebsite $portfolioWebsite, string $routeName): ?array
    {
        if (!$portfolioWebsite) {
            return null;
        }

        return match ($routeName) {
            'customer.portfolio.websites.edit' => [
                'label' => $portfolioWebsite->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite
                    ]
                ]
            ]
        };
    }
}
