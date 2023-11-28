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
use Aws\Result;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class SendIdentityEmailVerification
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(array $modelData): Result
    {
        return $this->getSesClient()->verifyEmailIdentity([
            'EmailAddress' => Arr::get($modelData, 'sender_email_address'),
        ]);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.edit");
    }

    public function rules(): array
    {
        return [
            'sender_email_address' => ['required', 'string', 'email']
        ];
    }

    public function asController(Shop $shop, ActionRequest $request): Result
    {
        $this->fillFromRequest($request);

        return $this->handle(
            modelData: $this->validateAttributes()
        );
    }
}
