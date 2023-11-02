<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Enums\Mail\MailshotTypeEnum;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\ActionRequest;
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
                'label' => 'Email',
                'value' => 'email'
            ],
            [
                'label' => 'First Name',
                'value' => 'first_name'
            ],
            [
                'label' => 'Last Name',
                'value' => 'last_name'
            ]
        ];
    }

    public function asController(): array
    {
        return $this->handle();
    }
}
