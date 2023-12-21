<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Dec 2023 19:12:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Mockery\Exception;

class SetPortfolioWebsiteIntegration
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    public function handle(PortfolioWebsite $portfolioWebsite, array $attributes): PortfolioWebsite
    {

        $portfolioWebsite = $this->update($portfolioWebsite, $attributes, ['integration_data']);
        return $portfolioWebsite;
    }


    public function rules(): array
    {
        return [
            'integration'      => ['required', Rule::enum(PortfolioWebsiteIntegrationEnum::class)],
            'integration_data' => ['sometimes', 'array']
        ];
    }


    public $commandSignature = 'portfolio-website:set-integration {portfolio_website} {integration} {--S|settings=}';

    public function asCommand(Command $command): int
    {
        try {
            $portfolioWebsite = PortfolioWebsite::where('slug', $command->argument('portfolio_website'))->firstOrFail();
        } catch (Exception) {
            $command->error("Portfolio website not found");

            return 1;
        }

        $this->fill(
            [
                'integration'      => $command->argument('integration'),
            ]
        );
        if($command->option('settings')) {


            $this->fill(
                [
                    'integration_data' => ['settings'=>json_decode($command->option('settings'), true)]
                ]
            );
        }


        $this->handle($portfolioWebsite, $this->validateAttributes());


        return 0;
    }

}
