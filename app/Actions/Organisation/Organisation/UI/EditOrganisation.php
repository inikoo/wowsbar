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
use Illuminate\Support\Arr;
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
        return $request->user()->hasPermissionTo("sysadmin.edit");
    }


    public function asController(): Organisation
    {
        return organisation();
    }


    public function htmlResponse(Organisation $organisation, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('Properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                "name" => [
                    "type"  => "input",
                    "label" => __("Name"),
                    "value" => $organisation->name
                ],
                "logo" => [
                    "type"  => "avatar",
                    "label" => __("logo"),
                    "value" => !blank($organisation->logo_id) ? $organisation->logoImageSources(320, 320) : null,
                ],
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('system settings'),
                'pageHead'    => [
                    'title' => __('system settings'),
                ],
                "formData"    => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    "args"      => [
                        "updateRoute" => [
                            "name" => "org.models.organisation.update"
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
                            'label' => __('settings'),
                        ]
                    ]
                ]
            );
    }
}
