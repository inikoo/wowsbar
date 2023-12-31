<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider;

use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeletePaymentServiceProvider
{
    use AsController;
    use WithAttributes;

    public function handle(PaymentServiceProvider $paymentServiceProvider): PaymentServiceProvider
    {
        $paymentServiceProvider->payments()->delete();
        $paymentServiceProvider->accounts()->delete();
        $paymentServiceProvider->delete();

        return $paymentServiceProvider;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("accounting.payment-service-providers.edit");
    }

    public function asController(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): PaymentServiceProvider
    {
        $request->validate();

        return $this->handle($paymentServiceProvider);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('org.accounting.payment-service-providers.index');
    }

}
