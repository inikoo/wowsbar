<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 06 Aug 2023 21:04:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Images\GetImgProxyUrl;
use App\Helpers\ImgProxy\Image;

beforeEach(function () {
    $imgSrc      = 'https://place-hold.it/300';
    $this->image = (new Image())->make($imgSrc);
    config(['img-proxy.base_url' => 'http://localhost:8080']);
});

test('Can create a imgproxy url.', function () {
    $image = $this->image;
    $url   = GetImgProxyUrl::run($image);
    expect($url)
        ->toBe('http://localhost:8080/0HAq4D77LoeLb8ndpc8kVphFwuDR0i_clOZ4PWPjS3k/aHR0cHM6Ly9wbGFjZS1ob2xkLml0LzMwMA');
});


test('Can create a resized imgproxy url.', function () {
    $image = $this->image;
    $image->resize(400, 400);

    expect(GetImgProxyUrl::make()->getProcessingOptions($image))
        ->toBe('rs:400:400::');
});
