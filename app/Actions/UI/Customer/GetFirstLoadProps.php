<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 13:34:01 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\WithLogo;
use App\Http\Resources\Assets\LanguageResource;
use App\Http\Resources\UI\CustomerAppResource;
use App\Models\Assets\Language;
use App\Models\Auth\User;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;
    use WithLogo;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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

        $firebaseAuthToken = null;


        if ($user) {
            $firebaseAuthToken = Cache::remember('customer_firebase_auth_token_'.$user->id, 3600, function () use ($user) {
                try {
                    $auth        = app('firebase.auth');
                    $customer    = customer();
                    $customToken = $auth
                        ->createCustomToken('customer-'.$user->id, [
                            'scope'         => 'customer',
                            'customer_slug' => $customer->slug
                        ]);

                    $auth->signInWithCustomToken($customToken);
                    $token = $customToken->toString();
                } catch (Exception) {
                    $token='';
                }

                return $token;
            });
        }


        return [

            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],

            'art' => $this->getArt(),
            'app' => CustomerAppResource::make(request()->get('website'))->getArray(),

            'layout' => function () use ($user) {
                if ($user) {
                    return GetLayout::run($user);
                } else {
                    return [
                        'publicUrl' => config('app.url')
                    ];
                }
            },

            'firebaseAuthToken' => $firebaseAuthToken,


        ];
    }

}
