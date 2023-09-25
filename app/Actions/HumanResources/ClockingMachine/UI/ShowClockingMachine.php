<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\HumanResources\Clocking\UI\IndexClockings;
use App\Actions\InertiaAction;
use App\Actions\UI\HumanResources\HumanResourcesDashboard;
use App\Enums\UI\ClockingMachineTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\HumanResources\ClockingMachineResource;
use App\Http\Resources\HumanResources\ClockingResource;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowClockingMachine extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('hr.edit');
        $this->canDelete = $request->user()->can('hr.edit');
        return $request->user()->hasPermissionTo("hr.view");
    }

    public function inOrganisation(ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $this->initialisation($request)->withTab(ClockingMachineTabsEnum::values());

        return $clockingMachine;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWorkplace(Workplace $workplace, ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $this->initialisation($request)->withTab(ClockingMachineTabsEnum::values());

        return $clockingMachine;
    }

    public function htmlResponse(ClockingMachine $clockingMachine, ActionRequest $request): Response
    {
        return Inertia::render(
            'HumanResources/ClockingMachine',
            [
                'title'                                 => __('clocking machine'),
                'breadcrumbs'                           => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'                            => [
                    'previous' => $this->getPrevious($clockingMachine, $request),
                    'next'     => $this->getNext($clockingMachine, $request),
                ],
                'pageHead'                              => [
                    'icon'  =>
                        [
                            'icon'  => ['fal', 'fa-chess-clock'],
                            'title' => __('clocking machines')
                        ],
                    'title'   => $clockingMachine->code,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false,
                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'org.hr.workplaces.show.clocking-machines.remove',
                                'parameters' => $request->route()->originalParameters()
                            ]

                        ] : false
                    ],
                    'meta'  => [
                        [
                            'name'     => trans_choice('clocking|clockings', 0/*$clockingMachine->stats->number_clockings*/),
                            'number'   => 0/*$clockingMachine->stats->number_clockings*/,
                            'href'     =>
                                match ($request->route()->getName()) {
                                    'org.hr.workplaces.show.clocking-machines.show' => [
                                        'org.hr.workplaces.show.clocking-machines.show.clockings.index',
                                        [$clockingMachine->workplace->slug, $clockingMachine->slug]
                                    ],
                                    default => [
                                        'hr.clocking-machines.show.clockings.index',
                                        $clockingMachine->slug
                                    ]
                                }


                            ,
                            'leftIcon' => [
                                'icon'    => 'fal fa-clock',
                                'tooltip' => __('clockings')
                            ]
                        ]
                    ]

                ],
                'tabs'                                  => [
                    'current'    => $this->tab,
                    'navigation' => ClockingMachineTabsEnum::navigation()
                ],
                ClockingMachineTabsEnum::SHOWCASE->value => $this->tab == ClockingMachineTabsEnum::SHOWCASE->value ?
                    fn () => GetClockingMachineShowcase::run($clockingMachine)
                    : Inertia::lazy(fn () => GetClockingMachineShowcase::run($clockingMachine)),

                ClockingMachineTabsEnum::CLOCKINGS->value => $this->tab == ClockingMachineTabsEnum::CLOCKINGS->value ?
                    fn () => ClockingResource::collection(IndexClockings::run($clockingMachine))
                    : Inertia::lazy(fn () => ClockingResource::collection(IndexClockings::run($clockingMachine))),

                ClockingMachineTabsEnum::HISTORY->value => $this->tab == ClockingMachineTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($clockingMachine))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($clockingMachine)))

            ]
        )->table(IndexClockings::make()->tableStructure())
            ->table(IndexHistories::make()->tableStructure());
    }


    public function jsonResponse(ClockingMachine $clockingMachine): ClockingMachineResource
    {
        return new ClockingMachineResource($clockingMachine);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        $headCrumb = function (ClockingMachine $clockingMachine, array $routeParameters, $suffix) {
            return [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('clocking machines')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $clockingMachine->slug,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],
            ];
        };
        return match ($routeName) {
            'hr.clocking-machines.show' =>
            array_merge(
                (new HumanResourcesDashboard())->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['clockingMachine'],
                    [
                        'index' => [
                            'name'       => 'hr.clocking-machines.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'hr.clocking-machines.show',
                            'parameters' => [
                                $routeParameters['clockingMachine']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            'org.hr.workplaces.show.clocking-machines.show' =>
            array_merge(
                (new \App\Actions\HumanResources\Workplace\UI\ShowWorkplace())->getBreadcrumbs($routeParameters['workplace']),
                $headCrumb(
                    $routeParameters['clockingMachine'],
                    [
                        'index' => [
                            'name'       => 'org.hr.workplaces.show.clocking-machines.index',
                            'parameters' => [$routeParameters['workplace']->slug]
                        ],
                        'model' => [
                            'name'       => 'org.hr.workplaces.show.clocking-machines.show',
                            'parameters' => [
                                $routeParameters['workplace']->slug,
                                $routeParameters['clockingMachine']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            default => []
        };
    }

    public function getPrevious(ClockingMachine $clockingMachine, ActionRequest $request): ?array
    {
        $previous = ClockingMachine::where('code', '<', $clockingMachine->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(ClockingMachine $clockingMachine, ActionRequest $request): ?array
    {
        $next = ClockingMachine::where('code', '>', $clockingMachine->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?ClockingMachine $clockingMachine, string $routeName): ?array
    {
        if (!$clockingMachine) {
            return null;
        }

        return match ($routeName) {
            'hr.clocking-machines.show' => [
                'label' => $clockingMachine->code,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'clockingMachine' => $clockingMachine->slug
                    ]

                ]
            ],
            'org.hr.workplaces.show.clocking-machines.show' => [
                'label' => $clockingMachine->code,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'workplace'         => $clockingMachine->workplace->slug,
                        'clockingMachine'   => $clockingMachine->slug
                    ]

                ]
            ]
        };
    }

}
