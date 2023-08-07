<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 06 Aug 2023 21:04:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Images\GetImgProxyUrl;
use App\Helpers\ImgProxy\Image;


test('Can create a imgproxy url.', function () {

    $imgSrc = 'https://place-hold.it/300';
    $image = (new Image)->make($imgSrc);
    $url = GetImgProxyUrl::run($image);
    expect($url)
        ->toBe('http://localhost:8080/0HAq4D77LoeLb8ndpc8kVphFwuDR0i_clOZ4PWPjS3k/aHR0cHM6Ly9wbGFjZS1ob2xkLml0LzMwMA');
});
