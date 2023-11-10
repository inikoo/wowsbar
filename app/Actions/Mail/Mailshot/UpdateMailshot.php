<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Mail\Mailshot;

class UpdateMailshot
{
    use WithActionUpdate;

    public function handle(Mailshot $mailshot, array $modelData): Mailshot
    {


        $mailshot = $this->update($mailshot, $modelData, ['data']);


        return $mailshot;
    }


}
