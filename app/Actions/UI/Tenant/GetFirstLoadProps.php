<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:00:42 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Firebase\ChangeRulesFirebase;
use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\User;
use Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
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

        $auth = app('firebase.auth');
        $tenant = app('currentTenant');

        if($user) {
            $customTokenFirebasePrefix = $tenant->slug;
            $cache                     = Cache::get($customTokenFirebasePrefix);

//            ChangeRulesFirebase::run(app('currentTenant'));

            if(blank($cache)) {
                $customToken = $auth
                    ->createCustomToken($tenant->slug, [
                        'tenant' => $tenant->slug
                    ]);

                $auth->signInWithCustomToken($customToken);

                Cache::put($customTokenFirebasePrefix, $customToken->toString(), 3600);
            }
        }

        return [
            'tenant'     => app('currentTenant') ? app('currentTenant')->only('name', 'code', 'logo_id') : null,
            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],



            'layout'   => function () use ($user) {
                if ($user) {
                    return GetLayout::run($user);
                } else {
                    return [

                        'logo'      => GetPictureSources::run(
                            (new Image())->make(url('/images/logo.png'))->resize(0, 64)
                        ),
                        'publicUrl' => config('app.url')


                    ];
                }
            },
            'firebase' => [
                'auth_token'  => $cache ?? null,
                'credential'  => File::get(base_path(config('firebase.projects.app.credentials.file'))),
                'databaseURL' => config('firebase.projects.app.database.url')
            ]
        ];
    }
}
