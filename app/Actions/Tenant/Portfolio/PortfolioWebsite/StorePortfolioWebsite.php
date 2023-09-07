<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydratePortfolioWebsites;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateUniversalSearch;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use App\Rules\CaseSensitive;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebsite
{
    use AsAction;
    use WithAttributes;


    /**
     * @var true
     */
    private bool $asAction = false;


    public function handle(array $modelData): PortfolioWebsite
    {
        $portfolioWebsite = PortfolioWebsite::create($modelData);
        $portfolioWebsite->stats()->create();
        TenantHydratePortfolioWebsites::dispatch(app('currentTenant'));
        PortfolioWebsiteHydrateUniversalSearch::dispatch($portfolioWebsite);

        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('portfolio_websites')],
            'code'   => ['required', 'unique:tenant.portfolio_websites', 'max:8'],
            'name'   => ['required']
        ];
    }

    public function asController(ActionRequest $request): PortfolioWebsite
    {
        $request->validate();

        return $this->handle($request->validated());
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite): RedirectResponse
    {
        return Redirect::route('portfolio.websites.show', [
            $portfolioWebsite->slug
        ]);
    }

    public function action(array $objectData): PortfolioWebsite
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'website:create {tenant} {domain} {code} {name}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        $this->asAction = true;
        $this->setRawAttributes(
            [
                'domain' => $command->argument('domain'),
                'code'   => $command->argument('code'),
                'name'   => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $portfolioWebsite=$this->handle($validatedData);

        $command->info("Done! website $portfolioWebsite->code created  🥳");
    }
}
