<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Market\ShopResource;
use App\Models\Market\Shop;
use App\Rules\AwsEmail;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;

class UpdateShopMailshotSetting
{
    use WithActionUpdate;
    use AwsClient;

    private bool $asAction = false;

    public function handle(Shop $shop, array $modelData): Shop
    {
        if(Arr::get($modelData, 'title') && Arr::get($modelData, 'description')) {
            $shop = $this->update($shop, $modelData, ['data', 'settings']);
        }

        if(Arr::get($modelData, 'sender_email_address')) {
            $shop = $this->update($shop, $modelData);

            $result = $this->getSesClient()->listIdentities([
                'IdentityType' => 'EmailAddress',
            ]);

            if (!in_array(Arr::get($modelData, 'sender_email_address'), ($result['Identities']))) {
                $this->getSesClient()->verifyEmailIdentity([
                    'EmailAddress' => Arr::get($modelData, 'sender_email_address'),
                ]);

                ValidationException::withMessages(['sender_email_address' => __('The email is not registered, we\'ve sent u verification to your email, please check your email.')]);
            }
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
            'sender_email_address'      => ['sometimes', 'nullable', 'string']
        ];
    }

    public function asController(Shop $shop, ActionRequest $request): Shop
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


    public function action(Shop $shop, $objectData): Shop
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shop, $validatedData);
    }

    public function jsonResponse(Shop $shop): ShopResource
    {
        return new ShopResource($shop);
    }

}
