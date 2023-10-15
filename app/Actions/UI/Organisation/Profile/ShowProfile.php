<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 09:24:49 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Profile;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowProfile
{
    use AsAction;
    use WithInertia;

    public function asController(ActionRequest $request): OrganisationUser
    {
        return $request->user();
    }

    public function jsonResponse(OrganisationUser $organisationUser): UserResource
    {
        return new UserResource($organisationUser);
    }

    public function htmlResponse(OrganisationUser $organisationUser, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('Profile'),
            'icon'   => 'fal fa-user-circle',
            'fields' => [
                "about"  => [
                    "type"  => "textarea",
                    "label" => __("about"),
                    "value" => $organisationUser->about,
                ],
                "avatar" => [
                    "type"  => "avatar",
                    "label" => __("photo"),
                    "value" => $organisationUser->avatarImageSources(320, 320)
                ],
            ]
        ];

        $sections['password'] = [
            'label'  => __('Password'),
            'icon'   => 'fal fa-key',
            'fields' => [
                "password" => [
                    "type"  => "password",
                    "label" => __("password"),
                    "value" => "",
                ],
            ]
        ];

        $sections['language'] = [
            'label'  => __('Language'),
            'icon'   => 'fal fa-language',
            'fields' => [
                "language_id" => [
                    "type"     => "language",
                    "label"    => __("language"),
                    "value"    => $organisationUser->language_id,
                    "options"  => GetLanguagesOptions::make()->translated(),
                    "canClear" => false
                ],
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }


        return Inertia::render("EditModel", [
            "title"       => __("Profile"),
            "breadcrumbs" => $this->getBreadcrumbs(),
            "pageHead"    => [
                "title" => __("My Profile"),
            ],
            "formData" => [
                'current'   => $currentSection,
                'blueprint' => $sections,
                "args"      => [
                    "updateRoute" => [
                        "name" => "org.models.profile.update"
                    ],
                ],
            ],
        ]);
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(ShowDashboard::make()->getBreadcrumbs(), [
            [
                "type"   => "simple",
                "simple" => [
                    "route" => [
                        "name" => "org.profile.show",
                    ],
                    "label" => __("my profile"),
                ],
            ],
        ]);
    }
}
