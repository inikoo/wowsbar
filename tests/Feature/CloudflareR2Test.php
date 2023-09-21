<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 06 Aug 2023 21:04:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */



test('Upload file to r2.', function () {

    $path    = resource_path('art/logo/logo-teal.png');
    $content = file_get_contents($path);
    $result  = Storage::disk('r2')->put('test.png', $content);

    expect($result)->toBeTrue();

});
