<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:01:15 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\PublicUser;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;

    public function handle(?PublicUser $user): array
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

            'art' => [
                'logo' => GetPictureSources::run(
                    (new Image())->make(url('/images/logo.png'))->resize(0, 64)
                ),
                'footer_logo' => GetPictureSources::run(
                    (new Image())->make(url('/images/logo_white.png'))->resize(0, 16)
                ),
            ],

            'layout'       => function () use ($user) {
                return $user ? GetLayout::run($user) : null;
            },
        ];
    }
}
