<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdatePortfolioSocialAccount
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(PortfolioSocialAccount $portfolioSocialAccount, array $modelData): PortfolioSocialAccount
    {
        return $this->update($portfolioSocialAccount, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'username'   => ['sometimes', 'string'],
            'url'        => ['sometimes', 'active_url'],
            'provider'   => ['sometimes', 'string', Rule::in(PortfolioSocialAccountPlatformEnum::values())]
        ];
    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): PortfolioSocialAccount
    {
        $request->validate();

        return $this->handle($portfolioSocialAccount, $request->validated());
    }

    public function action(PortfolioSocialAccount $portfolioSocialAccount, array $objectData): PortfolioSocialAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($portfolioSocialAccount, $validatedData);
    }
}
