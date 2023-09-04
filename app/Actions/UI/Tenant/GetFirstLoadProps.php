<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:00:42 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(?User $user): array
    {
        if ($user) {
            $language = $user->language;
        } else {
            $language = Language::where('code', App::currentLocale())->first();
        }
        if (!$language) {
            $language = Language::where('code', 'en')->first();
        }

        $firebaseAuthToken=null;

        if ($user) {
            $firebaseAuthToken = Cache::remember('tenant_firebase_auth_token_'.$user->id, 3600, function () {
                $auth        = app('firebase.auth');
                $tenant      = app('currentTenant');
                $customToken = $auth
                    ->createCustomToken($tenant->slug, [
                        'scope'       => 'tenant',
                        'tenant_slug' => $tenant->slug
                    ]);

                $auth->signInWithCustomToken($customToken);

                return $customToken->toString();
            });
        }


        return [
            'tenant'     => app('currentTenant') ? app('currentTenant')->only('name', 'code', 'logo_id', 'slug') : null,
            'user'       => $user,
            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],

            'art' => [
                'logo' => GetPictureSources::run(
                    (new Image())->make(url('/images/logo.png'))->resize(0, 64)
                ),
                'footer_logo' => GetPictureSources::run(
                    (new Image())->make(url('/images/logo_white.png'))->resize(0, 16)
                ),
            ],


            'layout'   => function () use ($user) {
                if ($user) {
                    return GetLayout::run($user);
                } else {
                    return [
                        'publicUrl' => config('app.url')
                    ];
                }
            },

            'firebaseAuthToken'  => $firebaseAuthToken,



        ];
    }
}
