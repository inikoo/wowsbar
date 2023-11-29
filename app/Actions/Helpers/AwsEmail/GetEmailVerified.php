<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\AwsEmail;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsCommand;

class GetEmailVerified
{
    use WithActionUpdate;
    use AwsClient;
    use AsCommand;

    public string $commandSignature = 'aws:email-verify';

    public function handle(): void
    {
        $shops = Shop::whereNull('sender_email_address_valid_at')->whereNotNull('sender_email_address')->get();

        $shops->map((function ($shop) {
            $result = $this->checkVerified($shop->sender_email_address);
            if (!blank($result)) {
                match ($result[$shop->sender_email_address]['VerificationStatus']) {
                    'Success' => $shop->update(['sender_email_address_valid_at' => now()]),
                    default   => $shop->update(['sender_email_address_valid_at' => null]),
                };
            }
        }));
    }

    public function checkVerified($email): array
    {
        return $this->getSesClient()->getIdentityVerificationAttributes([
            'Identities' => [$email]
        ])['VerificationAttributes'];
    }

    public function asCommand(): void
    {
        $this->handle();
    }
}
