<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Html;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\Browsershot\Browsershot;

class GetImageFromHtml
{
    use AsAction;
    use WithAttributes;

    public function handle($html)
    {

        $browserShot = Browsershot::html($html)
            ->setIncludePath('$PATH:/usr/bin')
            ->fullPage()
            ->setOption('newHeadless', true)
            ->base64Screenshot();

        $browserShot = str_replace('data:image/png;base64,', '', $browserShot);
        $browserShot = str_replace(' ', '+', $browserShot);


        $temp = tmpfile();



        fwrite($temp, base64_decode($browserShot));

        return $temp;


    }

}
