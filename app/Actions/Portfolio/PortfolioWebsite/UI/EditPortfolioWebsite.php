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
use Illuminate\Support\Str;
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
                        $request->route()->parameters()
                    ),
                    'navigation'   => [
                        'previous' => $this->getPrevious($portfolioWebsite, $request),
                        'next'     => $this->getNext($portfolioWebsite, $request),
                    ],
                    'pageHead'    => [
                        'title'     => __('Edit website'),
                        'container' => [
                            'icon'    => ['fal', 'fa-globe'],
                            'tooltip' => __('PortfolioWebsite'),
                            'label'   => Str::possessive($portfolioWebsite->name)
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
                                'title'  => __('ID/domain'),
                                'icon'   => 'fa-light fa-id-card',
                                'fields' => [
                                    'code' => [
                                        'type'     => 'input',
                                        'label'    => __('code'),
                                        'value'    => $portfolioWebsite->code,
                                        'required' => true,
                                    ],
                                    'name' => [
                                        'type'     => 'input',
                                        'label'    => __('name'),
                                        'value'    => $portfolioWebsite->name,
                                        'required' => true,
                                    ],
                                    'domain' => [
                                        'type'      => 'inputWithAddOn',
                                        'label'     => __('domain'),
                                        'leftAddOn' => [
                                            'label'=> 'http://www.'
                                        ],
                                        'value'    => $portfolioWebsite->domain,
                                        'required' => true,
                                    ],
                                ]
                            ],

                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'models.portfolio-website.update',
                                'parameters' => $portfolioWebsite->slug
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
        $previous = PortfolioWebsite::where('code', '<', $portfolioWebsite->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $next = PortfolioWebsite::where('code', '>', $portfolioWebsite->code)->orderBy('code')->first();

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
