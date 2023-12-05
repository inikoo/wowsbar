<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Surveys\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\WithElasticsearch;
use App\Enums\UI\Organisation\SurveysTabsEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Survey;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowCustomerSurvey extends InertiaAction
{
    use WithElasticsearch;


    private Customer|Organisation $parent;

    /** @noinspection PhpUnusedParameterInspection */
    public function inShop(Shop $shop, Survey $survey, ActionRequest $request): Survey
    {
        $this->initialisation($request)->withTab(SurveysTabsEnum::values());

        return $survey;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo("crm.view");
    }

    public function htmlResponse(Survey $survey, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/Customers/Surveys',
            [
                'title'       => __('survey'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($survey, $request),
                    'next'     => $this->getNext($survey, $request),
                ],
                'pageHead'    => [
                    'title'   => $survey->name,
                    'icon'    => [
                        'title' => __('survey'),
                        'icon'  => 'fal fa-terminal'
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : [],
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => SurveysTabsEnum::navigation()
                ],
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Survey $survey, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('users')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $survey->slug,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'org.crm.shop.customers.surveys.show',
            'org.crm.shop.customers.surveys.edit' =>

            array_merge(
                ShowCustomerSurvey::make()->getBreadcrumbs('org.crm.customers.show', $routeParameters),
                $headCrumb(
                    Survey::where('slug', $routeParameters['survey'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.customers.show.customer-users.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.customers.show.customer-users.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(Survey $survey, ActionRequest $request): ?array
    {
        $query    = Survey::where('slug', '<', $survey->slug);
        $previous = $query->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request);
    }

    public function getNext(Survey $survey, ActionRequest $request): ?array
    {
        $query = Survey::where('slug', '>', $survey->slug);
        $next  = $query->orderBy('slug')->first();

        return $this->getNavigation($next, $request);
    }

    private function getNavigation(?Survey $survey, ActionRequest $request): ?array
    {
        $routeName = $request->route()->getName();

        if (!$survey) {
            return null;
        }

        return match ($routeName) {
            'org.crm.shop.customers.surveys.show' => [
                'label' => $survey->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => array_merge($request->route()->originalParameters(), [$survey->slug])
                ]
            ]
        };
    }
}
