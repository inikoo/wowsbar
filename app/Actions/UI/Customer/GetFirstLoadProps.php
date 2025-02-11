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
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
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
    public function handle(?CustomerUser $customerUser): array
    {
        if ($customerUser) {
            $language = $customerUser->user->language;
        } else {
            $language = Language::where('code', App::currentLocale())->first();
        }
        if (!$language) {
            $language = Language::where('code', 'en')->first();
        }

        $firebaseAuthToken = null;


        if ($customerUser) {
            // $firebaseAuthToken = Cache::remember('customer_firebase_auth_token_'.$customerUser->user->id, 3600, function () use ($customerUser) {
            //     try {
            //         $auth     = app('firebase.auth');
            //         $customer = $customerUser->customer;



            //         $customToken = $auth
            //             ->createCustomToken('wow-user-'.$customerUser->user->ulid, [
            //                 'scope'                       => 'customer',
            //                 'customer_ulid'               => $customer->ulid,
            //                 'customer_user_ulid'          => $customerUser->user->ulid
            //             ]);

            //         $auth->signInWithCustomToken($customToken);
            //         $token = $customToken->toString();
            //     } catch (Exception) {
            //         $token = '';
            //     }

            //     return $token;
            // });
        }


        $app = CustomerAppResource::make(request()->get('website'))->getArray();

        if ($customerUser and $customer = Customer::find(session('customer_id'))) {
            $app['showLiveUsers'] = $customer->stats->number_customer_users_status_active > 1;
        }


        return [

            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],

            'art'    => $this->getArt(),
            'app'    => $app,
            'layout' => function () use ($customerUser) {
                if ($customerUser) {
                    return GetLayout::run($customerUser);
                } else {
                    return [
                        'publicUrl' => config('app.url')
                    ];
                }
            },

            // 'firebaseAuthToken' => $firebaseAuthToken,
            'environment'       => app()->environment(),


        ];
    }

}
