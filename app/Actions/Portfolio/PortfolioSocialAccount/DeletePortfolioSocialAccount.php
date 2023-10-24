<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\PortfolioSocialAccount;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeletePortfolioSocialAccount
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(PortfolioSocialAccount $portfolioSocialAccount): PortfolioSocialAccount
    {
        $portfolioSocialAccount->delete();

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

        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount): PortfolioSocialAccount
    {
        return $this->handle($portfolioSocialAccount);
    }
}
