<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\HumanResources\Employee\UI\IndexEmployees;
use App\Actions\InertiaAction;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Enums\UI\Organisation\JobPositionTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Http\Resources\HumanResources\JobPositionResource;
use App\Models\HumanResources\JobPosition;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowJobPosition extends InertiaAction
{
    use WithActionButtons;

    public function handle(JobPosition $jobPosition): JobPosition
    {
        return $jobPosition;
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = false;//$request->user()->hasPermissionTo('hr.edit');
        $this->canDelete = false;//$request->user()->hasPermissionTo('hr.edit');

        return $request->user()->hasPermissionTo("hr.view");
    }

    public function asController(JobPosition $jobPosition, ActionRequest $request): JobPosition
    {
        $this->initialisation($request)->withTab(JobPositionTabsEnum::values());

        return $this->handle($jobPosition);
    }

    public function htmlResponse(JobPosition $jobPosition, ActionRequest $request): Response
    {
        return Inertia::render(
            'HumanResources/JobPosition',
            [
                'title'       => __('position'),
                'breadcrumbs' => $this->getBreadcrumbs($jobPosition),
                'navigation'  => [
                    'previous' => $this->getPrevious($jobPosition, $request),
                    'next'     => $this->getNext($jobPosition, $request),
                ],
                'pageHead'    => [
                    'title'       => $jobPosition->name,
                    'actions'     => [
                        $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => JobPositionTabsEnum::navigation()
                ],

                JobPositionTabsEnum::SHOWCASE->value => $this->tab == JobPositionTabsEnum::SHOWCASE->value ?
                    fn () => GetJobPositionShowcase::run($jobPosition)
                    : Inertia::lazy(fn () => GetJobPositionShowcase::run($jobPosition)),

                JobPositionTabsEnum::EMPLOYEES->value => $this->tab == JobPositionTabsEnum::EMPLOYEES->value
                    ?
                    fn () => EmployeeResource::collection(
                        IndexEmployees::run(
                            parent: $jobPosition,
                            prefix: JobPositionTabsEnum::EMPLOYEES->value
                        )
                    )
                    : Inertia::lazy(fn () => EmployeeResource::collection(
                        IndexEmployees::run(
                            parent: $jobPosition,
                            prefix: JobPositionTabsEnum::EMPLOYEES->value
                        )
                    )),

                //               JobPositionTabsEnum::ROLES->value => $this->tab == JobPositionTabsEnum::ROLES->value
                //        ?
                //        fn () => RoleResource::collection(
                //            IndexRoles::run(
                //                parent: $jobPosition,
                //                prefix: 'roles'
                //            )
                //        )
                //        : Inertia::lazy(fn () => RoleResource::collection(
                //            IndexRoles::run(
                //                parent: $this->warehouse,
                //                prefix: 'roles'
                //            )
                //        )),


                JobPositionTabsEnum::HISTORY->value => $this->tab == JobPositionTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run($jobPosition))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run($jobPosition)))
            ]
        )->table(
            IndexEmployees::make()->tableStructure(
                parent: $jobPosition,
                prefix: JobPositionTabsEnum::EMPLOYEES->value
            )
        );
    }


    public function jsonResponse(JobPosition $jobPosition): JobPositionResource
    {
        return new JobPositionResource($jobPosition);
    }

    public function getBreadcrumbs(JobPosition $jobPosition, $suffix = null): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => [
                                'name' => 'org.hr.job-positions.index',
                            ],
                            'label' => __('positions')
                        ],
                        'model' => [
                            'route' => [
                                'name'       => 'org.hr.job-positions.show',
                                'parameters' => [$jobPosition->slug]
                            ],
                            'label' => $jobPosition->name,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],
            ]
        );
    }

    public function getPrevious(JobPosition $jobPosition, ActionRequest $request): ?array
    {
        $previous = JobPosition::where('slug', '<', $jobPosition->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(JobPosition $jobPosition, ActionRequest $request): ?array
    {
        $next = JobPosition::where('slug', '>', $jobPosition->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?JobPosition $jobPosition, string $routeName): ?array
    {
        if (!$jobPosition) {
            return null;
        }

        return match ($routeName) {
            'org.hr.job-positions.show' => [
                'label' => $jobPosition->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'jobPosition' => $jobPosition->slug
                    ]

                ]
            ]
        };
    }
}
