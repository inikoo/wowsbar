<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 09 Dec 2023 03:25:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Avatars;

use App\Enums\Helpers\Avatars\DiceBearStylesEnum;
use Exception;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class GetDiceBearAvatar
{
    use AsAction;

    public function handle(DiceBearStylesEnum $style, string $seed): string
    {
        try {
            $svg = file_get_contents("https://api.dicebear.com/7.x/".$style->value."/svg?seed=$seed");
        } catch (Exception) {
            $svg= Storage::disk('art')->get('avatars/shapes.svg');
        }
        return $svg;


    }

}
