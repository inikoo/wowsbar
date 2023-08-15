<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 19 May 2023 11:40:54 Malaysia Time, Kuala Lumpur, Malaysia
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
    public function handle(PortfolioWebsite $website): PortfolioWebsite
    {
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('portfolio.edit');
        return $request->user()->can("portfolio.edit");

    }

    public function asController(PortfolioWebsite $website, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request);
        return $this->handle($website);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(PortfolioWebsite $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/EditModel',
            [
                    'title'       => __("PortfolioWebsite's settings"),
                    'breadcrumbs' => $this->getBreadcrumbs(
                        $request->route()->getName(),
                        $request->route()->parameters()
                    ),
                    'navigation'   => [
                        'previous' => $this->getPrevious($website, $request),
                        'next'     => $this->getNext($website, $request),
                    ],
                    'pageHead'    => [
                        'title'     => __('Edit website'),
                        'container' => [
                            'icon'    => ['fal', 'fa-globe'],
                            'tooltip' => __('PortfolioWebsite'),
                            'label'   => Str::possessive($website->name)
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
                                    'parameters' => array_values($this->originalParameters)
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
                                        'value'    => $website->code,
                                        'required' => true,
                                    ],
                                    'name' => [
                                        'type'     => 'input',
                                        'label'    => __('name'),
                                        'value'    => $website->name,
                                        'required' => true,
                                    ],
                                    'domain' => [
                                        'type'      => 'inputWithAddOn',
                                        'label'     => __('domain'),
                                        'leftAddOn' => [
                                            'label'=> 'http://www.'
                                        ],
                                        'value'    => $website->domain,
                                        'required' => true,
                                    ],
                                ]
                            ],

                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'models.website.update',
                                'parameters' => $website->slug
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

    public function getPrevious(PortfolioWebsite $website, ActionRequest $request): ?array
    {
        $previous = PortfolioWebsite::where('code', '<', $website->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioWebsite $website, ActionRequest $request): ?array
    {
        $next = PortfolioWebsite::where('code', '>', $website->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioWebsite $website, string $routeName): ?array
    {
        if (!$website) {
            return null;
        }

        return match ($routeName) {
            'portfolio.websites.edit' => [
                'label' => $website->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'website' => $website->slug
                    ]
                ]
            ]
        };
    }
}
