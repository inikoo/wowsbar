<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 11:25:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Helpers;

use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class NaturalLanguage extends DefaultUrlGenerator
{
    use AsObject;

    public function fileSize(int $size, $precision = null): string
    {
        if ($size > 0) {
            $size     = (int)$size;
            $base     = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            $baseExponent=floor($base);
            if(is_null($precision)) {
                $precision=match((int) $baseExponent) {
                    0,1=>0,
                    default=> 1
                };
            }

            return round(pow(1024, $base - $baseExponent), $precision).$suffixes[$baseExponent];
        } else {
            return $size;
        }
    }
}
