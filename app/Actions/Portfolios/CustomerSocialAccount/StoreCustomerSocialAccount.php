<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerSocialAccount;

use App\Enums\SocialAccount\SocialAccountProviderEnum;
use App\Models\CRM\Customer;
use App\Models\Portfolios\CustomerSocialAccount;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCustomerSocialAccount
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Customer $customer, array $modelData): CustomerSocialAccount
    {
        data_set($modelData, 'shop_id', $customer->shop_id);

        /** @var \App\Models\Portfolios\CustomerSocialAccount $customerSocialAccount */
        $customerSocialAccount = $customer->socialAccounts()->create($modelData);

        return $customerSocialAccount;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function rules(): array
    {
        return [
            'url'        => ['required', 'active_url'],
            'provider'   => ['required', 'string', Rule::in(SocialAccountProviderEnum::values())]
        ];
    }

    public function asController(Customer $customer, ActionRequest $request): CustomerSocialAccount
    {
        $request->validate();

        return $this->handle($customer, $request->validated());
    }

    public function action(Customer $customer, array $objectData): CustomerSocialAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:social-account {customer} {url} {provider}';
    }

    public function asCommand(Command $command): int
    {
        $this->asAction = true;
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');

            return 1;
        }

        $this->setRawAttributes(
            [
                'domain' => $command->argument('url'),
                'code'   => $command->argument('provider')
            ]
        );
        $validatedData = $this->validateAttributes();

        $customerSocialAccount = $this->handle($customer, $validatedData);

        $command->info("Done! Account $customerSocialAccount->username created 🥳");

        return 0;
    }
}
