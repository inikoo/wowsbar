<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebpage;

use App\Actions\Portfolio\PortfolioWebpage\Hydrators\PortfolioWebpageHydrateUniversalSearch;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateWebpages;
use App\Actions\Traits\WithPortfolioWebsiteAction;
use App\Models\Portfolio\PortfolioWebpage;
use App\Models\Portfolio\PortfolioWebsite;
use App\Rules\IUnique;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebpage
{
    use AsAction;
    use WithAttributes;
    use WithPortfolioWebsiteAction;


    private bool $asAction = false;
    /**
     * @var array|\ArrayAccess|mixed
     */
    private PortfolioWebsite $portfolioWebsite;


    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): PortfolioWebpage
    {
        /** @var PortfolioWebpage $portfolioWebpage */
        $portfolioWebpage=$portfolioWebsite->portfolioWebpages()->create($modelData);
        $portfolioWebpage->stats()->create();
        PortfolioWebsiteHydrateWebpages::dispatch($portfolioWebsite);
        PortfolioWebpageHydrateUniversalSearch::dispatch($portfolioWebpage);
        return $portfolioWebpage;

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
            'url'  => [
                'required',
                'url',
                'max:500',
                new IUnique(
                    table: 'portfolio_webpages',
                    extraConditions: [
                        ['column' => 'portfolio_website_id', 'value' => $this->portfolioWebsite],
                    ]
                ),
            ],
            'name' => ['required', 'string', 'max:128']
        ];
    }








    public function action(PortfolioWebsite $portfolioWebsite, array $objectData): PortfolioWebpage
    {
        $this->asAction         = true;
        $this->portfolioWebsite = $portfolioWebsite;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($portfolioWebsite, $validatedData);
    }


}
