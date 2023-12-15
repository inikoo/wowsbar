<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Mon, 26 Jun 2023 14:30:24 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailAddress\Traits;

use Aws\Ses\SesClient;

trait AwsClient
{
    public function getSesClient(): SesClient
    {
        return new SesClient([
            'version'     => 'latest',
            'region'      => config('services.ses.region'),
            'credentials' => [
                'key'    => config('services.ses.key'),
                'secret' => config('services.ses.secret')
            ],
        ]);
    }
}
