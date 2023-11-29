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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class SendIdentityEmailVerification
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(Shop $shop, array $modelData): JsonResponse
    {
        $identities = GetListIdentityEmailVerification::run($modelData);

        data_set($modelData, 'sender_email_address', Arr::get($modelData, 'sender_email_address'));

        if (!in_array(Arr::get($modelData, 'sender_email_address'), ($identities))) {
            $result = $this->getSesClient()->verifyEmailIdentity([
                'EmailAddress' => Arr::get($modelData, 'sender_email_address'),
            ]);

            if ($result['@metadata']['statusCode'] != 200) {
                return response()->json(__('There was an error sending the verification email.'));
            }

            data_set($modelData, 'sender_email_address_valid_at', null);

            return response()->json(__('The email is not registered, we\'ve sent you verification to your email, please check your email.'));
        }

        $this->update($shop, $modelData);

        return response()->json(__('The email is validated.'));
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

    public function asController(Shop $shop, ActionRequest $request): JsonResponse
    {
        $this->fillFromRequest($request);

        return $this->handle(
            $shop,
            modelData: $this->validateAttributes()
        );
    }
}
