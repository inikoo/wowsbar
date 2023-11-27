<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Nov 2023 01:09:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Utils\IsGoogleIp;

test('is google ip', function () {
    expect(isGoogleIp::run('123.44.44.44'))->toBeFalse()
        ->and(isGoogleIp::run('66.249.89.16'))->toBeTrue();
});
