<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ContentBlockWebsite extends Pivot
{

    public $incrementing = true;

    protected $guarded = [];
}
