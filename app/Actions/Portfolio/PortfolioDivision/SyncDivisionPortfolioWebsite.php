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
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class SyncDivisionPortfolioWebsite
{
    use AsAction;
    use AsCommand;

    public string $commandSignature = 'division:sync {website} {division} {interest}';

    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): void
    {
        $divisions = Division::where('slug', $modelData['division'])->get();

        $portfolioWebsite->divisions()->attach($divisions->pluck('id'), [
            'interest' => $modelData['interest']
        ]);
    }

    public function rules(): array
    {
        return [
            'division' => ['required'],
            'interest' => ['required', Rule::in(InterestEnum::values())]
        ];
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): void
    {
        $request->validate();

        $this->handle($portfolioWebsite, $request->validated());
    }

    public function asCommand(Command $command): int
    {
        $modelData = [
            'interest' => $command->argument('interest'),
            'division' => $command->argument('division'),
        ];

        $portfolioWebsite = PortfolioWebsite::where('slug', $command->argument('website'))->first();

        $this->handle($portfolioWebsite, $modelData);

        echo $portfolioWebsite->name . " synced to " . $modelData['division'] . "\n";

        return 0;
    }
}
