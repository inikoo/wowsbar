<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class GetMailshotCustomText
{
    use AsAction;
    use WithAttributes;


    public function handle(): array
    {
        // TODO: Only for testing (need the actual data from json or database)

        return [
            [
                'name' => 'Raul',
                'value' => '{{name}}'
            ],
        ];
    }

    public function asController(): array
    {
        return $this->handle();
    }
}
