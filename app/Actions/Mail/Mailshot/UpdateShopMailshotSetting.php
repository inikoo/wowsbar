<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Helpers\AwsEmail\GetListIdentityEmailVerification;
use App\Actions\Helpers\AwsEmail\SendIdentityEmailVerification;
use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Market\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateShopMailshotSetting
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(Shop $shop, array $modelData): JsonResponse
    {
        if(Arr::get($modelData, 'title') && Arr::get($modelData, 'description')) {
            $shop = $this->update($shop, $modelData, ['data', 'settings']);
        }

        if(Arr::get($modelData, 'sender_email_address')) {
            $this->update($shop, $modelData);

            $identities = GetListIdentityEmailVerification::run($modelData);

            if (!in_array(Arr::get($modelData, 'sender_email_address'), ($identities))) {
                SendIdentityEmailVerification::run($modelData);

                return response()->json([
                    'status'  => 'needValidation',
                    'message' => __('The email is not registered, we\'ve sent u verification to your email, please check your email.')
                ]);
            }

            return response()->json([
                'status'  => 'validated',
                'message' => __('The email is validated.')
            ]);
        }

        return $shop;
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
            'title'                     => ['sometimes', 'required', 'string', 'max:255'],
            'description'               => ['sometimes', 'required', 'string', 'max:255'],
            'sender_email_address'      => ['sometimes', 'nullable', 'email']
        ];
    }

    public function asController(Shop $shop, ActionRequest $request): JsonResponse
    {
        $this->fillFromRequest($request);

        foreach ($this->validateAttributes() as $key => $value) {
            data_set(
                $modelData,
                match ($key) {
                    'title'       => 'settings.mailshot.unsubscribe.title',
                    'description' => 'settings.mailshot.unsubscribe.description',
                    default       => $key
                },
                $value
            );
        }

        return $this->handle(
            shop:$shop,
            modelData: $this->validateAttributes()
        );
    }
}
