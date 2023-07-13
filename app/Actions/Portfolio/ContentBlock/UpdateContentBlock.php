<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\WebsiteResource;
use App\Models\Portfolio\Website;
use Lorisleiva\Actions\ActionRequest;

class UpdateContentBlock
{
    use WithActionUpdate;


    public function handle(Website $website, array $modelData): Website
    {
        $contentBlock = $website->website();

        return $this->update($contentBlock, $modelData, ['data']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
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
