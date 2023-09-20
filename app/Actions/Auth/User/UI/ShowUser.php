<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

use App\Actions\Auth\UserRequest\ShowUserRequestLogs;
use App\Actions\Helpers\History\IndexHistories;
use App\Actions\Helpers\History\ShowHistories;
use App\Actions\InertiaAction;
use App\Actions\Traits\WithElasticsearch;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\UserTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\SysAdmin\UserRequestLogsResource;
use App\Http\Resources\SysAdmin\UserResource;
use App\Models\Auth\User;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowUser extends InertiaAction
{
    use WithElasticsearch;

    public function asController(User $user, ActionRequest $request): User
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        return $user;
    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('sysadmin.users.edit');
        return $request->user()->can("sysadmin.view");
    }

    public function htmlResponse(User $user, ActionRequest $request): Response
    {
        $this->validateAttributes();

        return Inertia::render(
            'SysAdmin/User',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($user, $request),
                    'next'     => $this->getNext($user, $request),
                ],
                'pageHead' => [
                    'title'   => $user->username,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => UserTabsEnum::navigation()
                ],

                UserTabsEnum::SHOWCASE->value => $this->tab == UserTabsEnum::SHOWCASE->value ?
                    fn () => new UserResource($user)
                    : Inertia::lazy(fn () => new UserResource($user)),

                UserTabsEnum::REQUEST_LOGS->value => $this->tab == UserTabsEnum::REQUEST_LOGS->value ?
                    fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($user->username))
                    : Inertia::lazy(fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($user->username))),

                UserTabsEnum::HISTORY->value => $this->tab == UserTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(ShowHistories::run($user))
                    : Inertia::lazy(fn () => HistoryResource::collection(ShowHistories::run($user)))

            ]
        )->table(ShowUserRequestLogs::make()->tableStructure())
            ->table(IndexHistories::make()->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {

        $headCrumb = function (User $user, array $routeParameters, string $suffix) {
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
                            'label' => $user->username,
                        ],

                    ],
                    'suffix' => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'sysadmin.users.show',
            'sysadmin.users.edit' =>

            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['user'],
                    [
                        'index' => [
                            'name'       => 'sysadmin.users.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'sysadmin.users.show',
                            'parameters' => [$routeParameters['user']->username]
                        ]
                    ],
                    $suffix
                ),
            ),


            default => []
        };

    }

    public function getPrevious(User $user, ActionRequest $request): ?array
    {
        $previous = User::where('username', '<', $user->username)->orderBy('username', 'desc')->first();
        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(User $user, ActionRequest $request): ?array
    {
        $next = User::where('username', '>', $user->username)->orderBy('username')->first();
        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?User $user, string $routeName): ?array
    {
        if (!$user) {
            return null;
        }
        return match ($routeName) {
            'sysadmin.users.show' => [
                'label' => $user->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'user' => $user->username
                    ]

                ]
            ]
        };
    }
}
