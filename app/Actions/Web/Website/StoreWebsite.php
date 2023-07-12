<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 18 Oct 2022 11:30:40 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;


use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateWeb;
use App\Models\Web\Website;
use App\Rules\CaseSensitive;
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
       $tenant=app('currentTenant');
        /** @var Website $website */
        $website = $tenant->websites()->create($modelData);
        $website->stats()->create();
        TenantHydrateWeb::run(app('currentTenant'));
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('websites')],
            'code'   => ['required', 'unique:tenant.websites','max:8'],
            'name'   => ['required']
        ];
    }

    public function asController( ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($request->validated());
    }

    public function htmlResponse(Website $website): RedirectResponse
    {
        return Redirect::route('websites.show', [
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
}
