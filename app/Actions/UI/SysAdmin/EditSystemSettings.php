<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 06 Jun 2023 14:48:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\SysAdmin;

use App\Actions\UI\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditSystemSettings
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("sysadmin.edit");
    }


    public function asController()
    {

    }


    public function htmlResponse(): Response
    {

        $tenant= app('currentTenant');
        return Inertia::render(
            'EditModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('system settings'),
                'pageHead'    => [
                    'title' => __('system settings'),
                ],
                "formData" => [
                    "blueprint" => [

                        [
                            "title"  => __("branding"),
                            "icon"   => "fa-light fa-copyright",
                            "fields" => [
                                "name" => [
                                    "type"  => "input",
                                    "label" => __("Name"),
                                    "value" => $tenant->name
                                ],
                                "logo" => [
                                    "type"  => "avatar",
                                    "label" => __("logo"),
                                    "value" => $tenant->logo_id,
                                ],
                            ],
                        ],
                        // [
                        //     "title"  => __("appearance"),
                        //     "icon"   => "fa-light fa-paint-brush",
                        //     "fields" => [
                        //         "colorMode" => [
                        //             "type"  => "colorMode",
                        //             "label" => __("turn dark mode"),
                        //             "value" => "",
                        //         ],
                        //         "theme"     => [
                        //             "type"  => "theme",
                        //             "label" => __("choose your theme"),
                        //             "value" => "",
                        //         ],
                        //     ],
                        // ],
                    ],
                    "args"      => [
                        "updateRoute" => [
                            "name"       => "models.system-settings.update"
                        ],
                    ],
                ],


            ]
        );
    }



    public function getBreadcrumbs(): array
    {
        return
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'sysadmin.settings.edit'
                            ],
                            'label'  => __('settings'),
                        ]
                    ]
                ]
            );
    }
}
