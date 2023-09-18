<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:00:42 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\WithLogo;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;
    use WithLogo;


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
            $firebaseAuthToken = Cache::remember('tenant_firebase_auth_token_'.$user->id, 3600, function () use ($user) {
                $auth        = app('firebase.auth');
                $tenant      = customer();
                $customToken = $auth
                    ->createCustomToken('tenant-'.$user->id, [
                        'scope'       => 'tenant',
                        'tenant_slug' => $tenant->slug
                    ]);

                $auth->signInWithCustomToken($customToken);

                return $customToken->toString();
            });
        }


        return [
            'tenant'     => customer() ? customer()->only('name', 'code', 'logo_id', 'slug') : null,
            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],

            'art'=> $this->getArt(),

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
