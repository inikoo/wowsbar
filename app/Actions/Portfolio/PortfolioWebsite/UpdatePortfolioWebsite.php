<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 18 Oct 2022 11:30:40 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;

class UpdatePortfolioWebsite
{
    use WithActionUpdate;


    public function handle(PortfolioWebsite $website, array $modelData): PortfolioWebsite
    {
        $this->update($website, $modelData, ['data']);

        PortfolioWebsiteHydrateUniversalSearch::dispatch($website);

        return $website;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }


    public function rules(): array
    {
        return [
            'domain' => ['sometimes','required'],
            'code'   => ['sometimes','required', 'unique:tenant.portfolio_websites','max:8'],
            'name'   => ['sometimes','required']
        ];
    }

    public function asController(PortfolioWebsite $website, ActionRequest $request): PortfolioWebsite
    {
        $request->validate();

        return $this->handle($website, $request->all());
    }



    public function jsonResponse(PortfolioWebsite $website): PortfolioWebsiteResource
    {
        return new PortfolioWebsiteResource($website);
    }
}
