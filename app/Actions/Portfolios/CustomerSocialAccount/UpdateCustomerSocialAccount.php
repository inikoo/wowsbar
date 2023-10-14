<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerSocialAccount;

use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use App\Models\Portfolios\CustomerSocialAccount;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateCustomerSocialAccount
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(CustomerSocialAccount $customerSocialAccount, array $modelData): CustomerSocialAccount
    {
        $customerSocialAccount->update($modelData);

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
            'provider'   => ['required', 'string', Rule::in(PortfolioSocialAccountPlatformEnum::values())]
        ];
    }

    public function asController(CustomerSocialAccount $customerSocialAccount, ActionRequest $request): CustomerSocialAccount
    {
        $request->validate();

        return $this->handle($customerSocialAccount, $request->validated());
    }

    public function action(CustomerSocialAccount $customerSocialAccount, array $objectData): CustomerSocialAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customerSocialAccount, $validatedData);
    }
}
