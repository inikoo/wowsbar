<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Portfolio\ContentBlock\Hydrators\ContentBlockHydrateUniversalSearch;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateContentBlocks;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Lorisleiva\Actions\ActionRequest;

class UpdateContentBlock
{
    use WithActionUpdate;

    public function handle(ContentBlock $contentBlock, array $modelData): ContentBlock
    {
        $this->update($contentBlock, $modelData, ['data', 'layout']);

        ContentBlockHydrateUniversalSearch::dispatch($contentBlock);
        TenantHydrateContentBlocks::dispatch(app('currentTenant'));

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'code'   => ['sometimes','required', 'unique:tenant.websites','max:8'],
            'name'   => ['sometimes','required'],
            'layout'   => ['sometimes','required']
        ];
    }

    public function asController(ContentBlock $contentBlock, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($contentBlock, $request->all());
    }

    public function jsonResponse(ContentBlock $website): ContentBlockResource
    {
        return new ContentBlockResource($website);
    }
}
