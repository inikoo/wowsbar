<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\Assets\LanguageResource;
use App\Http\Resources\Organisation\OrganisationResource;
use App\Models\Assets\Language;
use App\Models\Organisation\Organisation;
use App\Models\Organisation\OrganisationUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Lorisleiva\Actions\Concerns\AsObject;

class GetFirstLoadProps
{
    use AsObject;


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(?OrganisationUser $user): array
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
                    (new Image())->make(url('/images/logo_white.png'))->resize(0, 64)
                ),
            ],


            'layout'       => function () use ($user) {
                return $user ? GetLayout::run($user) : null;
            },
            'organisation' => OrganisationResource::make(Organisation::first())->getArray(),
            'firebase'     => [
                'credential'  => File::get(base_path(config('firebase.projects.app.credentials.file'))),
                'databaseURL' => config('firebase.projects.app.database.url')
            ]
        ];
    }
}
