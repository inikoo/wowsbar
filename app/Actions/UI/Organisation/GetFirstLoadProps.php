<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\WithLogo;
use App\Http\Resources\Assets\LanguageResource;
use App\Http\Resources\Organisation\OrganisationResource;
use App\Http\Resources\UI\CustomerAppResource;
use App\Http\Resources\UI\OrganisationAppResource;
use App\Models\Assets\Language;
use App\Models\Auth\OrganisationUser;
use App\Models\Organisation\Organisation;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;
    use WithLogo;


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(?OrganisationUser $user): array
    {
        $firebaseAuthToken = null;

        if ($user) {
            $language = $user->language;

            $firebaseAuthToken = Cache::remember('org_firebase_auth_token_'.$user->id, 3600, function () use ($user) {
                try {
                    $auth        = app('firebase.auth');
                    $customToken = $auth
                        ->createCustomToken('org-'.$user->id, [
                            'scope' => 'organisation',
                        ]);
                    $auth->signInWithCustomToken($customToken);
                    $token = $customToken->toString();
                } catch (Exception) {
                    $token='';
                }
                return  $token;
            });
        } else {
            $language = Language::where('code', App::currentLocale())->first();
        }
        if (!$language) {
            $language = Language::where('code', 'en')->first();
        }

        $app = OrganisationAppResource::make(organisation())->getArray();

        return [
            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],

            'art'               => $this->getArt(),
            'app'               => $app,
            'layout'            => function () use ($user) {
                return $user ? GetLayout::run($user) : null;
            },
            'organisation'      => OrganisationResource::make(Organisation::first())->getArray(),
            'firebaseAuthToken' => $firebaseAuthToken,

        ];
    }
}
