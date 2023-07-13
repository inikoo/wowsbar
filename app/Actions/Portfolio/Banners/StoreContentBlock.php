<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 18 Oct 2022 11:30:40 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banners;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateWeb;
use App\Models\Portfolio\Website;
use App\Rules\CaseSensitive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreContentBlock
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;
    public function handle(Website $website, array $modelData): Model
    {
        $website->website()->create($modelData);

        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(Website $website, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($website, $request->validated());
    }
    public function action(Website $website, array $objectData): Model
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $validatedData);
    }
}
