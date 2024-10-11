<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Accounting\PaymentServiceProviderResource;
use App\Models\Accounting\PaymentServiceProvider;
use Lorisleiva\Actions\ActionRequest;

class UpdatePaymentServiceProvider
{
    use WithActionUpdate;

    private bool $asAction=false;

    public function handle(PaymentServiceProvider $paymentServiceProvider, array $modelData): PaymentServiceProvider
    {
        return $this->update($paymentServiceProvider, $modelData, ['data']);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        return $request->user()->hasPermissionTo("inventory.warehouses.edit");
    }

    public function rules(): array
    {
        return [
            'code'         => ['sometimes', 'required', 'unique:payment_service_providers', 'between:2,9', 'alpha_dash'],
        ];
    }

    public function asController(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): PaymentServiceProvider
    {
        $request->validate();
        return $this->handle($paymentServiceProvider, $request->all());
    }

    public function action(PaymentServiceProvider $paymentServiceProvider, $objectData): PaymentServiceProvider
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($paymentServiceProvider, $validatedData);
    }

    public function jsonResponse(PaymentServiceProvider $paymentServiceProvider): PaymentServiceProviderResource
    {
        return new PaymentServiceProviderResource($paymentServiceProvider);
    }
}
