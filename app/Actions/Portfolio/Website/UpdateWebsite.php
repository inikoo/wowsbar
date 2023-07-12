<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 18 Oct 2022 11:30:40 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Website;

use App\Actions\WithActionUpdate;
use App\Http\Resources\Portfolio\WebsiteResource;
use App\Models\Portfolio\Website;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsite
{
    use WithActionUpdate;


    public function handle(Website $website, array $modelData): Website
    {
        return $this->update($website, $modelData, ['data']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("websites.edit");
    }


    public function rules(): array
    {
        return [
            'domain' => ['sometimes','required'],
            'code'   => ['sometimes','required', 'unique:tenant.websites','max:8'],
            'name'   => ['sometimes','required']
        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website, $request->all());
    }



    public function jsonResponse(Website $website): WebsiteResource
    {
        return new WebsiteResource($website);
    }
}
