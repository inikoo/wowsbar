<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeletePortfolioWebsite
{
    use AsController;
    use WithAttributes;

    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        $portfolioWebsite->delete();

        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $request->validate();
        return $this->handle($portfolioWebsite);
    }



    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('customer.portfolio.websites.index');
    }

}
