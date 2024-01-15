<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebpage;

use App\Actions\Portfolio\PortfolioWebpage\Hydrators\PortfolioWebpageHydrateUniversalSearch;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\PortfolioWebpageResource;
use App\Models\Portfolio\PortfolioWebpage;
use App\Rules\IUnique;
use Lorisleiva\Actions\ActionRequest;

class UpdatePortfolioWebpage
{
    use WithActionUpdate;



    private PortfolioWebpage $portfolioWebpage;

    private bool $asAction=false;

    public function handle(PortfolioWebpage $portfolioWebpage, array $modelData): PortfolioWebpage
    {
        $portfolioWebpage = $this->update($portfolioWebpage, $modelData, ['data']);
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


    public function rules(ActionRequest $request): array
    {


        return [
            'url'  => [
                'sometimes',
                'required',
                'string',
                'max:500',
                new IUnique(
                    table: 'portfolio_webpages',
                    extraConditions: [
                        ['column' => 'portfolio_website_id', 'value' => $this->portfolioWebpage->portfolio_website_id],
                        ['column' => 'id', 'operator' => '!=', 'value' => $this->portfolioWebpage->id]
                    ]
                ),

            ],
            'name' => ['sometimes', 'required', 'string', 'max:128']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if($request->exists('url')) {
            $request->replace([
                'url' => 'https://' . $request->input('url')
            ]);
        }

    }

    public function asController(PortfolioWebpage $portfolioWebpage, ActionRequest $request): PortfolioWebpage
    {
        $this->portfolioWebpage =$portfolioWebpage;
        $request->validate();
        return $this->handle($portfolioWebpage, $request->validated());
    }

    public function action(PortfolioWebpage $portfolioWebpage, array $modelData): PortfolioWebpage
    {
        $this->asAction  = true;

        $this->portfolioWebpage =$portfolioWebpage;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();
        return $this->handle($portfolioWebpage, $validatedData);
    }

    public function jsonResponse(PortfolioWebpage $portfolioWebpage): PortfolioWebpageResource
    {
        return new PortfolioWebpageResource($portfolioWebpage);
    }
}
