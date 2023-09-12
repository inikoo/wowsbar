<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 15:22:42 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\UI;

use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditOrganisation
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("sysadmin.edit");
    }


    public function asController(): Organisation
    {
        return organisation();
    }


    public function htmlResponse(Organisation $organisation): Response
    {

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
                                    "value" => $organisation->name
                                ],
                                "logo" => [
                                    "type"  => "avatar",
                                    "label" => __("logo"),
                                    "value" => $organisation->logo_id,
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
                            "name"       => "org.models.organisation.update"
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
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'org.sysadmin.organisation.edit'
                            ],
                            'label'  => __('settings'),
                        ]
                    ]
                ]
            );
    }
}
