<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioDivision;

use App\Enums\Helpers\Interest\InterestEnum;
use App\Models\Organisation\Division;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncDivisionPortfolioWebsite
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): PortfolioWebsite
    {
        $divisions = Division::where('slug', $modelData['division'])->get();

        $portfolioWebsite->divisions()->syncWithPivotValues($divisions->pluck('id'), [
            'interest' => $modelData['interest']
        ]);

        return $portfolioWebsite;
    }

    public function rules(): array
    {
        return [
            'division' => ['required'],
            'interest' => ['required', Rule::in(InterestEnum::values())]
        ];
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $request->validate();

        return $this->handle($portfolioWebsite, $request->validated());
    }
}
