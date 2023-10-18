<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Payment;

use App\Actions\Accounting\Payment\Hydrators\PaymentHydrateUniversalSearch;
use App\Actions\Accounting\PaymentGateway\Xendit\Channels\Invoice\MakePaymentUsingInvoice;
use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Enums\Accounting\Payment\PaymentStatusEnum;
use App\Models\Accounting\Payment;
use App\Models\Accounting\PaymentAccount;
use App\Models\CRM\Customer;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePayment
{
    use AsAction;
    use WithAttributes;

    private bool $asAction   = false;
    public $commandSignature = 'payment:create {reference} {amount}';

    /**
     * @throws \Throwable
     */
    public function handle(Customer $customer, PaymentAccount $paymentAccount, array $modelData)
    {
        return DB::transaction(function () use ($customer, $paymentAccount, $modelData) {
            $modelData['customer_id'] = $customer->id;
            $modelData['shop_id']     = $customer->shop_id;

            $modelData['org_amount'] = $modelData['amount'];

            data_fill($modelData, 'date', gmdate('Y-m-d H:i:s'));

            /** @var \App\Models\Accounting\Payment $payment */
            $payment = $paymentAccount->payments()->create($modelData);

            match ($paymentAccount->paymentServiceProvider->code) {
                'xendit' => MakePaymentUsingInvoice::run($payment),
                default  => null
            };

            PaymentHydrateUniversalSearch::dispatch($payment);

            return $payment;
        });
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("accounting.edit");
    }

    public function asController(ActionRequest $request): Response
    {
        $request->validate();

        return $this->handle(customer(), '', $request->validated());
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string'],
            'status'    => ['sometimes', 'required', Rule::in(PaymentStatusEnum::values())],
            'state'     => ['sometimes', 'required', Rule::in(PaymentStateEnum::values())],
            'amount'    => ['required', 'decimal:0,2']
        ];
    }

    public function htmlResponse(Payment $payment): Response
    {
        return Arr::get($payment->data, 'invoice_url');
    }

    public function action(Customer $customer, PaymentAccount $paymentAccount, array $objectData): Payment
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $paymentAccount, $validatedData);
    }

    public function asCommand(Command $command): int
    {
        $this->asAction=true;
        $this->setRawAttributes([
            'reference' => $command->argument('reference'),
            'amount'    => $command->argument('amount')
        ]);

        $customer       = Customer::first();
        $paymentAccount = PaymentAccount::where('code', 'xendit')->first();

        $validatedData = $this->validateAttributes();
        $this->handle($customer, $paymentAccount, $validatedData);

        echo "Successfully create payment \n";

        return 0;
    }
}
