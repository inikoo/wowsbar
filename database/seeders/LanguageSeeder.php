<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 26 Aug 2021 04:30:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Assets\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $validLanguages=['en','es','sk','zh-Hans','id','ja','sk','fr','de'];


        $languages = json_decode(Storage::disk('datasets')->get('languages.json'));


        foreach ($languages as $language) {
            Language::upsert(
                [
                                     [
                                         'code'  => $language->code,
                                         'name'  => $language->name,
                                         'status'=> in_array($language->code, $validLanguages),
                                         'data'  => json_encode([])
                                     ],
                                 ],
                ['code'],
                ['name','status']
            );
        }
    }
}
