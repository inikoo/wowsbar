<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Portfolio\ContentBlock\Banners\UI\ParseContentBlockLayout;
use App\Actions\Portfolio\ContentBlock\Hydrators\ContentBlockHydrateUniversalSearch;
use App\Actions\Portfolio\ContentBlockComponent\StoreContentBlockComponent;
use App\Actions\Portfolio\ContentBlockComponent\UpdateContentBlockComponent;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateContentBlocks;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\ContentBlockComponent;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateContentBlock
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(ContentBlock $contentBlock, array $modelData): ContentBlock
    {


        if(Arr::has($modelData, 'layout')) {
            $layout                                =Arr::pull($modelData, 'layout');
            list($layout, $contentBlockComponents) = ParseContentBlockLayout::run($layout, $contentBlock->webBlock);
            data_set($modelData, 'layout', $layout);

            if ($contentBlockComponents) {
                foreach ($contentBlockComponents as $ulid=>$contentBlockComponentData) {

                    $contentBlockComponent=ContentBlockComponent::where('ulid', $ulid)->first();
                    if($contentBlockComponent) {



                        UpdateContentBlockComponent::run(
                            $contentBlockComponent,
                            Arr::only($contentBlockComponentData, ['layout','imageData'])
                        );
                    } else {
                        data_set($contentBlockComponent, 'ulid', $ulid);
                        StoreContentBlockComponent::run(
                            contentBlock: $contentBlock,
                            modelData: $contentBlockComponentData,
                        );
                    }
                    /*

                    */
                }
            }

        }

        $this->update($contentBlock, $modelData, ['data','layout']);

        ContentBlockHydrateUniversalSearch::dispatch($contentBlock);
        TenantHydrateContentBlocks::dispatch(app('currentTenant'));

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'code'   => ['sometimes', 'required', 'unique:tenant.websites', 'max:8'],
            'name'   => ['sometimes', 'required'],
            'layout' => ['sometimes', 'required','array:delay,common,components']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {

        $request->merge(
            [
                'layout' => $request->only(['delay', 'common', 'components'])
            ]
        );

    }

    public function asController(ContentBlock $contentBlock, ActionRequest $request): ContentBlock
    {
        $request->validate();
        return $this->handle($contentBlock, $request->validated());
    }

    public function action(ContentBlock $contentBlock, $modelData): ContentBlock
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($contentBlock, $validatedData);
    }

    public function jsonResponse(ContentBlock $website): ContentBlockResource
    {
        return new ContentBlockResource($website);
    }
}
