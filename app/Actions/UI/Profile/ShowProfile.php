<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 24 Apr 2023 20:22:54 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Profile;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\SysAdmin\UserResource;
use App\Models\Auth\User;
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

    public function htmlResponse(User $user): Response
    {
        $this->validateAttributes();

        return Inertia::render("EditModel", [
            "title"       => __("Profile"),
            "breadcrumbs" => $this->getBreadcrumbs(),
            "pageHead"    => [
                "title" => __("My Profile"),
            ],


            "formData" => [
                "blueprint" => [
                    [
                        "title"   => __("profile"),
                        "icon"    => "fa-light fa-user-circle",
                        "notes"   => __(
                            "This information will be synchronised in all your workspaces."
                        ),
                        "current" => true,
                        "fields"  => [
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
                                "label" => __("photo"),
                                "value" => '',
                            ],

                        ],
                    ],
                    [
                        "title"  => __("password"),
                        "icon"   => "fa-light fa-key",
                        "fields" => [
                            "password" => [
                                "type"  => "password",
                                "label" => __("password"),
                                "value" => "",
                            ],
                        ],
                    ],
                    [
                        "title"  => __("language"),
                        "icon"   => "fal fa-language",
                        "fields" => [
                            "language_id" => [
                                "type"    => "language",
                                "label"   => __("language"),
                                "value"   => $user->language_id,
                                'options' => GetLanguagesOptions::make()->translated(),

                            ],
                        ],
                    ],
                    [
                        "title"  => __("appearance"),
                        "icon"   => "fa-light fa-paint-brush",
                        "fields" => [
                            "colorMode" => [
                                "type"  => "colorMode",
                                "label" => __("turn dark mode"),
                                "value" => "",
                            ],
                            "theme"     => [
                                "type"  => "theme",
                                "label" => __("choose your theme"),
                                "value" => "",
                            ],
                        ],
                    ],
                    [
                        "title"  => __("notifications"),
                        "icon"   => "fa-light fa-bell",
                        "fields" => [
                            "notifications" => [
                                "type"  => "myNotifications",
                                "label" => __("notifications"),
                                "value" => [],
                                "data"  => [
                                    [
                                        'type' => 'new-order',
                                        'label'=> __('new order'),
                                    ],
                                    [
                                        'type' => 'new re',
                                        'label'=> __('new order'),
                                    ],
                                    [
                                        'type' => 'new user',
                                        'label'=> __('new order'),
                                    ],
                                ]
                            ],

                        ],
                    ],
                ],
                "args"      => [
                    "updateRoute" => [
                        "name"       => "models.profile.update"
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
                        "name" => "profile.show",
                    ],
                    "label" => __("my profile"),
                ],
            ],
        ]);
    }
}
