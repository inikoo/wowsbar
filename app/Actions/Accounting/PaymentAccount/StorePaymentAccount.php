<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 27 Feb 2023 11:19:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentAccount;

use App\Actions\Accounting\PaymentServiceProvider\Hydrators\PaymentServiceProviderHydrateAccounts;
use App\Models\Accounting\PaymentAccount;
use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePaymentAccount
{
    use AsAction;
    use WithAttributes;

    private bool $asAction   =false;
    public $commandSignature = 'pa:create {code} {name}';

    public function handle(PaymentServiceProvider $paymentServiceProvider, array $modelData): PaymentAccount
    {
        /** @var PaymentAccount $paymentAccount */
        $paymentAccount = $paymentServiceProvider->accounts()->create($modelData);
        $paymentAccount->stats()->create();
        PaymentServiceProviderHydrateAccounts::dispatch($paymentServiceProvider);

        return $paymentAccount;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        return $request->user()->hasPermissionTo("accounting.edit");
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.payment_accounts', 'between:2,9', 'alpha_dash'],
            'name' => ['required', 'max:250', 'string'],
        ];
    }

    public function action(PaymentServiceProvider $paymentServiceProvider, array $objectData): PaymentAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($paymentServiceProvider, $validatedData);
    }

    public function asCommand(Command $command): int
    {
        $this->asAction=true;
        $this->setRawAttributes([
            'code' => $command->argument('code'),
            'name' => $command->argument('name')
        ]);

        $paymentServiceProvider = PaymentServiceProvider::where('code', 'xendit')->first();

        $validatedData = $this->validateAttributes();
        $this->handle($paymentServiceProvider, $validatedData);

        echo "Successfully create payment account \n";

        return 0;
    }
}
