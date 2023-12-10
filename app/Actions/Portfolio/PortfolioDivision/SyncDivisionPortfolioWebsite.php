<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioDivision;

use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateWelcomeStep;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteInterestEnum;
use App\Models\SysAdmin\Division;
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

        $portfolioWebsite->divisions()->updateExistingPivot($divisions->pluck('id'), [
            'interest' => $modelData['interest']
        ]);

        OrganisationHydrateCustomerWebsites::dispatch();
        CustomerHydratePortfolioWebsites::dispatch($portfolioWebsite->customer);


        if (in_array($modelData['interest'], [
            PortfolioWebsiteInterestEnum::NOT_INTERESTED,
            PortfolioWebsiteInterestEnum::INTERESTED,
        ])) {
            CustomerHydrateWelcomeStep::make()->interestSet($portfolioWebsite->customer);
        } elseif ($modelData['interest'] == PortfolioWebsiteInterestEnum::CUSTOMER) {

            CustomerHydrateWelcomeStep::make()->isCustomer($portfolioWebsite->customer);
        }

    }

    public function rules(): array
    {
        return [
            'division' => ['required', 'exists:divisions,slug'],
            'interest' => ['required', Rule::in(PortfolioWebsiteInterestEnum::values())]
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

        echo $portfolioWebsite->name." synced to ".$modelData['division']."\n";

        return 0;
    }
}
