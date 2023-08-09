<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 12 May 2023 10:34:26 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\RootUser;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsObject;

class GetLandlordFirstLoadProps
{
    use AsObject;

    public function handle(?RootUser $user): array
    {
        if ($user) {
            $language = $user->language;
        } else {
            $language = Language::where('code', App::currentLocale())->first();
        }
        if (!$language) {
            $language = Language::where('code', 'en')->first();
        }


        return [
            'localeData' =>
                [
                    'language'        => LanguageResource::make($language)->getArray(),
                    'languageOptions' => GetLanguagesOptions::make()->translated(),
                ],


            'layout' => function () use ($user) {
                if ($user) {
                    return GetLandlordLayout::run($user);
                } else {
                    return [
                        'logo' => GetPictureSources::run(
                            (new Image())->make(url('/images/logo.png'))->resize(0, 64)
                        ),
                    ];
                }
            }
        ];
    }
}
