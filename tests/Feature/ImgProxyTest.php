<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 06 Aug 2023 21:04:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\ImgProxy\GetImgProxyUrl;
use App\Helpers\ImgProxy\Image;


test('Can create a imgproxy url.', function () {
    $imgSrc='https://place-hold.it/300';

    $image          = (new Image)->make($imgSrc);

    $url=GetImgProxyUrl::run($image);
    dd($url);
    expect($url)->toBeString();
});
