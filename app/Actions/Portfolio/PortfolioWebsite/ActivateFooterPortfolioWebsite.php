<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;

class ActivateFooterPortfolioWebsite
{
    use WithActionUpdate;

    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        return $this->update($portfolioWebsite, [
            'footer_status' => true
        ]);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        return $this->handle($portfolioWebsite);
    }
}
