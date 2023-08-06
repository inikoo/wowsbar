<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 06 Aug 2023 01:12:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

use App\Models\Media\Media;
use Lorisleiva\Actions\Concerns\AsAction;

class GetImageUrl
{
    use AsAction;


    public function handle(Media $media): string
    {
        return '';
    }



}
