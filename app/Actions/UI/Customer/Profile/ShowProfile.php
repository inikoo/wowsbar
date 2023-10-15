<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Profile;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\Auth\UserResource;
use App\Models\Auth\User;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowProfile
{
    use AsAction;
    use WithInertia;

    public function asController(ActionRequest $request): User
    {
        return $request->user();
    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function htmlResponse(User $user, ActionRequest $request): Response
    {

        $sections['properties'] = [
            'label'  => __('Profile'),
            'icon'   => 'fal fa-user-circle',
            'fields' => [
                "email"  => [
                    "type"  => "input",
                    "label" => __("email"),
                    "value" => $user->email,
                ],
                "about"  => [
                    "type"  => "textarea",
                    "label" => __("about"),
                    "value" => $user->about,
                ],
                "avatar" => [
                    "type"  => "avatar",
                    "label" => __("avatar"),
                    "value" => !blank($user->avatar_id) ? $user->avatarImageSources(320, 320) : null,
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
                    "value"    => $user->language_id,
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
                        "name" => "customer.models.profile.update"
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
                        "name" => "customer.profile.show",
                    ],
                    "label" => __("my profile"),
                ],
            ],
        ]);
    }
}
