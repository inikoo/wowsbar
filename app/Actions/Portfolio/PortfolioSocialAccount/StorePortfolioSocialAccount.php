<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount;

use App\Actions\Traits\WithSocialAudit;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolios\CustomerSocialAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioSocialAccount
{
    use AsAction;
    use WithAttributes;
    use WithSocialAudit;
    private bool $asAction = false;

    public function handle(array $modelData): PortfolioSocialAccount
    {
        $customer = customer();

        data_set($modelData, 'shop_id', $customer->shop_id);
        data_set($modelData, 'customer_id', $customer->id);

        /** @var PortfolioSocialAccount $portfolioSocialAccount */
        $portfolioSocialAccount = PortfolioSocialAccount::create($modelData);
        $this->createAudit(CustomerSocialAccount::find($portfolioSocialAccount->id));

        return $portfolioSocialAccount;
    }



    public function htmlResponse(): RedirectResponse
    {
        return redirect()->route('customer.portfolio.social-accounts.index');
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.social.edit");
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'url'      => ['sometimes', 'active_url', 'max:1000'],
            'platform' => ['required', new Enum(PortfolioSocialAccountPlatformEnum::class)]
        ];
    }

    public function asController(ActionRequest $request): PortfolioSocialAccount
    {
        $request->validate();

        return $this->handle($request->validated());
    }

    public function action(array $objectData): PortfolioSocialAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }

}
