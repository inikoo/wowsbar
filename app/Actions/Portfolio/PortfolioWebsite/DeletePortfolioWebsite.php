<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 12:15:45 Malaysia Time, Kuala Lumpur, Malaysia
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

    public function handle(PortfolioWebsite $website): PortfolioWebsite
    {
        $website->delete();

        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(PortfolioWebsite $website, ActionRequest $request): PortfolioWebsite
    {
        $request->validate();
        return $this->handle($website);
    }



    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('portfolio.websites.index');
    }

}
