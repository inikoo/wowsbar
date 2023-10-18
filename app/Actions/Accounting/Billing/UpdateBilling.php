<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Billing;

use App\Actions\Accounting\Payment\Hydrators\PaymentHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Accounting\PaymentResource;
use App\Models\Accounting\Payment;
use Lorisleiva\Actions\ActionRequest;

class UpdateBilling
{
    use WithActionUpdate;

    private bool $asAction=false;

    public function handle(Payment $payment, array $modelData): Payment
    {
        $payment = $this->update($payment, $modelData, ['data']);
        PaymentHydrateUniversalSearch::dispatch($payment);
        return $payment;
    }
    public function authorize(ActionRequest $request): bool
    {
        if($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("accounting.edit");
    }
    public function rules(): array
    {
        return [
            'reference'         => ['sometimes', 'required', 'unique:payments', 'between:2,64', 'alpha_dash'],
        ];
    }
    public function action(Payment $payment, array $objectData): Payment
    {
        $this->asAction=true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($payment, $validatedData);
    }


    public function asController(Payment $payment, ActionRequest $request): Payment
    {
        $request->validate();
        return $this->handle($payment, $request->all());
    }

    public function jsonResponse(Payment $payment): PaymentResource
    {
        return new PaymentResource($payment);
    }
}
