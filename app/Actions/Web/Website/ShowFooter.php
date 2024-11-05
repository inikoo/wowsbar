<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Thu, 25 Apr 2024 16:56:22 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Portfolio\PortfolioWebsite\Traits\HasPortfolioWebsiteSubNavigation;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Actions\Web\Website\UI\GetWebsiteWorkshopFooter;
use App\Http\Resources\Web\WebBlockTypesResource;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Web\WebBlockType;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowFooter
{
    use AsAction;
    use HasPortfolioWebsiteSubNavigation;


    private Website $website;

    private Webpage|Website|PortfolioWebsite $parent;

    private Shop $scope;

    public bool $asAction = false;

    public function handle(PortfolioWebsite $website): PortfolioWebsite
    {
        return $website;
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        $subNavigation = $this->getSubNavigation($request);

        return Inertia::render(
            'Footer/FooterWorkshop',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('footer'),
                'pageHead'    => [
                    'title'    => $portfolioWebsite->name,
                    'icon'     => [
                        'title' => __('footer'),
                        'icon'  => 'fal fa-browser'
                    ],
                    'actions'            => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                        /* [
                            'type'  => 'button',
                            'style' => 'primary',
                            'icon'  => ["fas", "fa-rocket"],
                            'label' => __('Publish'),
                            'route' => [
                                'method'     => 'post',
                                'name'       => 'customer.models.banner.workshop.footers.publish.footer',
                            ]
                        ], */
                    ],
                    'subNavigation'    => $subNavigation,
                ],

                'uploadImageRoute' => [
                    'name'       => 'grp.models.website.footer.images.store',
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite->id
                    ]
                ],

                'autosaveRoute' => [
                    'name'       => 'customer.models.portfolio-website.footers.autosave',
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite->id
                    ]
                ],

                'publishRoute' => [
                    'name'       => 'customer.models.portfolio-website.footers.publish',
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite->id
                    ]
                ],

                'data'       => GetWebsiteWorkshopFooter::run($portfolioWebsite),
                'web_blocks' => WebBlockTypesResource::collection(WebBlockType::all())
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->parent = $portfolioWebsite;

        return $this->parent;
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = 'Footer'): array
    {
        $headCrumb = function (string $type, PortfolioWebsite $portfolioWebsite, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $portfolioWebsite->slug,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $portfolioWebsite->slug
                    ],
                    'suffix'         => '('.$suffix.')'
                ],
            ];
        };

        return array_merge(
            ShowPortfolio::make()->getBreadcrumbs(),
            $headCrumb(
                'modelWithIndex',
                PortfolioWebsite::firstWhere('slug', $routeParameters['portfolioWebsite']),
                [
                    'index' => [
                        'name'       => 'customer.banners.websites.index',
                        'parameters' => []
                    ],
                    'model' => [
                        'name'       => 'customer.banners.websites.show',
                        'parameters' => $routeParameters
                    ]
                ],
                $suffix
            ),
        );
    }

}
