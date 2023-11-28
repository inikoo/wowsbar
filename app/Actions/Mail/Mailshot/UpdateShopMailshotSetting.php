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

    public function handle(Shop $shop, array $modelData): JsonResponse|Shop
    {
        if(Arr::get($modelData, 'title') or Arr::get($modelData, 'description')) {
            $modelData = Arr::except($modelData, ['title', 'description']);

            $shop = $this->update($shop, $modelData, ['data', 'settings']);
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

    public function asController(Shop $shop, ActionRequest $request): JsonResponse|Shop
    {
        $this->fillFromRequest($request);
        $modelData = $this->validateAttributes();

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
            modelData: $modelData
        );
    }
}
