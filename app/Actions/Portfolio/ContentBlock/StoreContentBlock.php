<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:48:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydratePortfolio;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use App\Models\Web\WebBlock;
use App\Models\Web\WebBlockType;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreContentBlock
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(Website $website, WebBlock $webBlock, array $modelData): ContentBlock
    {
        data_set($modelData, 'web_block_type_id', $webBlock->web_block_type_id);
        data_set($modelData, 'tenant_id', app('currentTenant')->id);
        data_set($modelData, 'layout', $webBlock->blueprint);

        /** @var ContentBlock $contentBlock */
        $contentBlock = $webBlock->contentBlocks()->create($modelData);

        $website->contentBlocks()->attach($contentBlock->id, [
            'tenant_id' => app('currentTenant')->id,
            'ulid'      => Str::ulid()
        ]);
        TenantHydratePortfolio::make()->contentBlocks(app('currentTenant'));

        return $contentBlock;
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
            'code' => ['required', 'unique:tenant.content_blocks', 'max:8'],
            'name' => ['required']
        ];
    }

    public function inWebsiteInWebBlockType(Website $website, WebBlockType $webBlockType, ActionRequest $request): ContentBlock
    {
        $request->validate();
        $webBlock = $webBlockType->webBlocks[0];

        return $this->handle($website, $webBlock, $request->validated());
    }

    public function action(Website $website, WebBlock $webBlock, array $objectData): ContentBlock
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $webBlock, $validatedData);
    }
}
