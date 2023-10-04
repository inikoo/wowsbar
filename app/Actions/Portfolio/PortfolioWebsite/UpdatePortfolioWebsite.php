<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\IUnique;
use Lorisleiva\Actions\ActionRequest;

class UpdatePortfolioWebsite
{
    use WithActionUpdate;


    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): PortfolioWebsite
    {
        $portfolioWebsite = $this->update($portfolioWebsite, $modelData, ['data']);
        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);

        return $portfolioWebsite;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
    }


    public function rules(ActionRequest $request): array
    {
        $currentID = $request->route()->parameters()['portfolioWebsite']->id;

        return [
            'url'  => [
                'sometimes',
                'required',
                'url',
                'max:500',
                new IUnique(
                    table: 'portfolio_websites',
                    extraConditions: [
                        ['column' => 'customer_id', 'value' => customer()->id],
                        ['column' => 'id', 'operator' => '!=', 'value' => $currentID]
                    ]
                ),

            ],
            'name' => ['sometimes', 'required', 'string', 'max:128']
        ];
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $request->validate();
        return $this->handle($portfolioWebsite, $request->validated());
    }


    public function jsonResponse(PortfolioWebsite $portfolioWebsite): PortfolioWebsiteResource
    {
        return new PortfolioWebsiteResource($portfolioWebsite);
    }
}
