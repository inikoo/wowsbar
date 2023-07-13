<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:48:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use App\Models\Web\WebBlock;
use App\Models\Web\WebBlockType;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreContentBlock
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(Website $website, WebBlock $webBlock, array $modelData): Model
    {
        data_set($modelData, 'web_block_type_id', $webBlock->web_block_type_id);

        /** @var ContentBlock $contentBlock */
        $contentBlock = $webBlock->contentBlocks()->create($modelData);

        $website->contentBlocks()->attach($contentBlock->id);

        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }


    public function inWebsiteInWebBlockType(Website $website, WebBlockType $webBlockType, ActionRequest $request): Model
    {
        $request->validate();
        // todo change this
        // need to get the WebBlock from the createUI (if is more than one banner/WebBlock type) if only one just fetch it from db
        $webBlock = $webBlockType->webBlocks[0];
        //-------
        return $this->handle($website, $webBlock, $request->validated());
    }

    public function action(Website $website, WebBlock $webBlock, array $objectData): Model
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $webBlock, $validatedData);
    }
}
