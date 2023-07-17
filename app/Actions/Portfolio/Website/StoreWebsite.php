<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 18 Oct 2022 11:30:40 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Website;

use App\Actions\Portfolio\Website\Hydrators\WebsiteHydrateUniversalSearch;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateWebsites;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use App\Rules\CaseSensitive;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreWebsite
{
    use AsAction;
    use WithAttributes;


    /**
     * @var true
     */
    private bool $asAction = false;


    public function handle(array $modelData): Website
    {

        $website = Website::create($modelData);
        $website->stats()->create();
        TenantHydrateWebsites::dispatch(app('currentTenant'));
        WebsiteHydrateUniversalSearch::run($website);

        return $website;
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
            'domain' => ['required', new CaseSensitive('websites')],
            'code'   => ['required', 'unique:tenant.websites', 'max:8'],
            'name'   => ['required']
        ];
    }

    public function asController(ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($request->validated());
    }

    public function htmlResponse(Website $website): RedirectResponse
    {
        return Redirect::route('portfolio.websites.show', [
            $website->slug
        ]);
    }

    public function action(array $objectData): Website
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

        $website=$this->handle($validatedData);

        $command->info("Done! website $website->code created  🥳");
    }
}
