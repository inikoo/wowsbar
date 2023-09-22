<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:49:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\UI\CRM;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\MailroomTabsEnum;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowMailroomDashboard extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.view");
    }


    public function asController(ActionRequest $request): Organisation
    {
        $this->initialisation($request)->withTab(MailroomTabsEnum::values());
        $request->validate();

        return organisation();
    }

    public function htmlResponse(Organisation|Shop $parent, ActionRequest $request): Response
    {
        $routeName       = $request->route()->getName();
        $routeParameters = $request->route()->originalParameters();

        return Inertia::render(
            'CRM/MailroomDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $routeName,
                    $routeParameters
                ),
                'title'       => 'CRM',
                'pageHead'    => [
                    'title' => __('mailroom'),
                    'icon'  => [
                        'title' => __('mailroom'),
                        'icon'  => 'fal fa-envelope'
                    ],
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => MailroomTabsEnum::navigation()
                ],
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters=[]): array
    {
        return array_merge(
            ShowDashboard::make()->getBreadcrumbs(),
            [
                match ($routeName) {
                    'org.crm.dashboard' => [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name'       => 'org.crm.shop.dashboard',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('CRM'),
                        ]
                    ],
                    default => [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name'       => 'org.crm.shop.mailroom.dashboard',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('CRM'),
                        ]
                    ]
                }
            ]
        );
    }

}
