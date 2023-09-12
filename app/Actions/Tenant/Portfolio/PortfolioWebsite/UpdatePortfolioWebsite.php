<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite;

use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;

class UpdatePortfolioWebsite
{
    use WithActionUpdate;


    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): PortfolioWebsite
    {
        $portfolioWebsite=$this->update($portfolioWebsite, $modelData, ['data']);
        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);
        return $portfolioWebsite;
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

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->fillFromRequest($request);
        return $this->handle($portfolioWebsite, $this->validateAttributes());
    }


    public function jsonResponse(PortfolioWebsite $portfolioWebsite): PortfolioWebsiteResource
    {
        return new PortfolioWebsiteResource($portfolioWebsite);
    }
}
