<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:01:15 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\UI\WithLogo;
use App\Http\Resources\Assets\LanguageResource;
use App\Models\Assets\Language;
use App\Models\Auth\PublicUser;
use Illuminate\Support\Facades\App;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;
    use WithLogo;

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

            'art'=>$this->getArt(),
            'layout'       => function () use ($user) {
                return $user ? GetLayout::run($user) : [
                    'header' => [
                        'component' => 'HeaderThemeOne',
                        'data'      => []
                    ],
                    'menu'   => [
                        'component' => 'MenuOne',
                        'data'      => []
                    ],
                    'footer' => [
                        'component' => 'FooterThemeTwo',
                        'data'      => []
                    ]
                ];
            },
        ];
    }
}
