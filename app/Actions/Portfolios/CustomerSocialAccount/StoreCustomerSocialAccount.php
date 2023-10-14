<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerSocialAccount;

use App\Actions\Traits\WithSocialAudit;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolios\CustomerSocialAccount;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreCustomerSocialAccount
{
    use AsAction;
    use WithAttributes;
    use WithSocialAudit;

    private bool $asAction = false;

    public function handle(Customer $customer, array $modelData): CustomerSocialAccount
    {
        data_set($modelData, 'shop_id', $customer->shop_id);

        /** @var CustomerSocialAccount $customerSocialAccount */
        $customerSocialAccount = $customer->socialAccounts()->create($modelData);

        $this->createAudit(PortfolioSocialAccount::find($customerSocialAccount->id));

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
            'username' => ['required', 'string', 'max:255'],
            'url'      => ['sometimes', 'active_url', 'max:1000'],
            'platform' => ['required', new Enum(PortfolioSocialAccountPlatformEnum::class)]
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
        return 'customer:social-account {customer} {platform} {username}';
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
                'platform' => $command->argument('platform'),
                'username' => $command->argument('username')
            ]
        );
        $validatedData = $this->validateAttributes();

        $customerSocialAccount = $this->handle($customer, $validatedData);

        $command->info("Done! Social account $customerSocialAccount->platform  $customerSocialAccount->username created 🥳");

        return 0;
    }
}
