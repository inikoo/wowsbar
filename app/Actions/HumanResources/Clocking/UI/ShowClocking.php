<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Clocking\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\HumanResources\ClockingMachine\UI\ShowClockingMachine;
use App\Actions\InertiaAction;
use App\Actions\UI\HumanResources\HumanResourcesDashboard;
use App\Enums\UI\ClockingTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

/**
 * @property \App\Models\HumanResources\Clocking $clocking
 */
class ShowClocking extends InertiaAction
{
    public function handle(Clocking $clocking): Clocking
    {
        return $clocking;
    }
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('hr.edit');
        $this->canDelete = $request->user()->can('hr.edit');
        return $request->user()->hasPermissionTo("hr.view");
    }


    public function inOrganisation(Clocking $clocking, ActionRequest $request): Clocking
    {
        $this->initialisation($request)->withTab(ClockingTabsEnum::values());
        return $this->handle($clocking);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWorkplace(Workplace $workplace, Clocking $clocking, ActionRequest $request): Clocking
    {
        $this->initialisation($request)->withTab(ClockingTabsEnum::values());
        return $this->handle($clocking);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inClockingMachine(ClockingMachine $clockingMachine, Clocking $clocking, ActionRequest $request): Clocking
    {
        $this->initialisation($request)->withTab(ClockingTabsEnum::values());
        return $this->handle($clocking);
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function inWorkplaceInClockingMachine(Workplace $workplace, ClockingMachine $clockingMachine, Clocking $clocking, ActionRequest $request): Clocking
    {
        $this->initialisation($request)->withTab(ClockingTabsEnum::values());
        return $this->handle($clocking);
    }

    public function htmlResponse(Clocking $clocking, ActionRequest $request): Response
    {
        return Inertia::render(
            'HumanResources/Clocking',
            [
                'title'       => __('clocking'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'                            => [
                    'previous' => $this->getPrevious($clocking, $request),
                    'next'     => $this->getNext($clocking, $request),
                ],
                'pageHead'    => [
                    'icon'  =>
                        [
                            'icon'  => ['fal', 'fa-clock'],
                            'title' => __('clocking')
                        ],
                    'title'   => $clocking->slug,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false,
                        $this->canDelete ?
                            match ($request->route()->getName()) {
                                'hr.clockings.show' => [
                                    'type'  => 'button',
                                    'style' => 'delete',
                                    'route' => [
                                        'name'       => 'hr.clockings.remove',
                                        'parameters' => $request->route()->originalParameters()
                                    ],

                                ],
                                'org.hr.workplaces.show.clockings.show' => [
                                    'type'  => 'button',
                                    'style' => 'delete',
                                    'route' => [
                                        'name'       => 'org.hr.workplaces.show.clockings.remove',
                                        'parameters' => $request->route()->originalParameters()
                                    ],
                                ],
                                'org.hr.workplaces.show.clocking-machines.show.clockings.show' => [
                                    'type'  => 'button',
                                    'style' => 'delete',
                                    'route' => [
                                        'name'       => 'org.hr.workplaces.show.clocking-machines.show.clockings.remove',
                                        'parameters' => $request->route()->originalParameters()
                                    ]
                                ]
                            } : false
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ClockingTabsEnum::navigation()

                ],

                ClockingTabsEnum::SHOWCASE->value => $this->tab == ClockingTabsEnum::SHOWCASE->value ?
                    fn () => GetClockingShowcase::run($clocking)
                    : Inertia::lazy(fn () => GetClockingShowcase::run($clocking)),

                ClockingTabsEnum::HISTORY->value => $this->tab == ClockingTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($clocking))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($clocking)))
            ]
        )->table(IndexHistories::make()->tableStructure());
    }


    public function jsonResponse(Clocking $clocking): JsonResource
    {
        return new JsonResource($clocking);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Clocking $clocking, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('clockings')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $clocking->slug,
                        ],

                    ],
                    'suffix'=> $suffix
                ],
            ];
        };

        return match ($routeName) {
            'hr.clockings.show' =>
            array_merge(
                HumanResourcesDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['clocking'],
                    [
                        'index' => [
                            'name'       => 'hr.clockings.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'hr.clockings.show',
                            'parameters' => [$routeParameters['clocking']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.hr.workplaces.show.clockings.show' => array_merge(
                (new \App\Actions\HumanResources\Workplace\UI\ShowWorkplace())->getBreadcrumbs($routeParameters['workplace']),
                $headCrumb(
                    $routeParameters['clocking'],
                    [
                        'index' => [
                            'name'       => 'org.hr.workplaces.show.clockings.index',
                            'parameters' => [
                                $routeParameters['workplace']->slug,
                            ]
                        ],
                        'model' => [
                            'name'       => 'org.hr.workplaces.show.clockings.show',
                            'parameters' => [
                                $routeParameters['workplace']->slug,
                                $routeParameters['clocking']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            'hr.clocking-machines.show.clockings.show' => array_merge(
                (new ShowClockingMachine())->getBreadcrumbs(
                    'hr.clocking-machines.show',
                    [
                       'clockingMachine' => $routeParameters['clockingMachine']
                    ]
                ),
                $headCrumb(
                    $routeParameters['clocking'],
                    [
                        'index' => [
                            'name'       => 'hr.clocking-machines.show.clockings.index',
                            'parameters' => [
                                $routeParameters['clockingMachine']->slug,
                            ]
                        ],
                        'model' => [
                            'name'       => 'hr.clocking-machines.show.clockings.show',
                            'parameters' => [
                                $routeParameters['clockingMachine']->slug,
                                $routeParameters['clocking']->slug
                            ]
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.hr.workplaces.show.clocking-machines.show.clockings.show' => array_merge(
                (new ShowClockingMachine())->getBreadcrumbs(
                    'org.hr.workplaces.show.clocking-machines.show',
                    [
                      'workplace'       => $routeParameters['workplace'],
                      'clockingMachine' => $routeParameters['clockingMachine'],
                    ]
                ),
                $headCrumb(
                    $routeParameters['clocking'],
                    [
                        'index' => [
                            'name'       => 'org.hr.workplaces.show.clocking-machines.show.clockings.index',
                            'parameters' => [
                                $routeParameters['workplace']->slug,
                                $routeParameters['clockingMachine']->slug,
                            ]
                        ],
                        'model' => [
                            'name'       => 'org.hr.workplaces.show.clocking-machines.show.clockings.show',
                            'parameters' => [
                                $routeParameters['workplace']->slug,
                                $routeParameters['clockingMachine']->slug,
                                $routeParameters['clocking']->slug
                            ]
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(Clocking $clocking, ActionRequest $request): ?array
    {
        $previous=Clocking::where('slug', '<', $clocking->slug)->when(true, function ($query) use ($clocking, $request) {
            switch ($request->route()->getName()) {
                case 'org.hr.workplaces.show.clockings.show':
                    $query->where('clockings.workplace_id', $clocking->workplace_id);
                    break;
                case 'org.hr.workplaces.show.clocking-machines.show.clockings.show':
                case 'hr.clocking-machines.show.clockings.show':
                    $query->where('clockings.clocking_machine_id', $clocking->clocking_machine_id);
                    break;

            }
        })->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(Clocking $clocking, ActionRequest $request): ?array
    {
        $next = Clocking::where('slug', '>', $clocking->slug)->when(true, function ($query) use ($clocking, $request) {
            switch ($request->route()->getName()) {
                case 'org.hr.workplaces.show.clockings.show':
                    $query->where('clockings.workplace_id', $clocking->workplace_id);
                    break;
                case 'org.hr.workplaces.show.clocking-machines.show.clockings.show':
                case 'hr.clocking-machines.show.clockings.show':
                    $query->where('clockings.clocking_machine_id', $clocking->clocking_machine_id);
                    break;

            }
        })->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Clocking $clocking, string $routeName): ?array
    {
        if(!$clocking) {
            return null;
        }
        return match ($routeName) {
            'hr.clockings.show'=> [
                'label'=> $clocking->slug,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'clocking'  => $clocking->slug
                    ]

                ]
            ],
            'hr.clocking-machines.show.clockings.show' => [
                'label'=> $clocking->slug,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'clockingMachine'   => $clocking->clockingMachine->slug,
                        'clocking'          => $clocking->slug
                    ]

                ]
            ],
            'org.hr.workplaces.show.clockings.show'=> [
                'label'=> $clocking->slug,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'workplace' => $clocking->workplace->slug,
                        'clocking'  => $clocking->slug
                    ]

                ]
            ],
            'org.hr.workplaces.show.clocking-machines.show.clockings.show' => [
                'label'=> $clocking->slug,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'workplace'         => $clocking->workplace->slug,
                        'clockingMachine'   => $clocking->clockingMachine->slug,
                        'clocking'          => $clocking->slug
                    ]

                ]
            ]
        };
    }

}
