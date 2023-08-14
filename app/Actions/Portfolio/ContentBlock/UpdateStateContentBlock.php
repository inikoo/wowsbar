<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\ContentBlock\ContentBlockStateEnum;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\ContentBlock;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateStateContentBlock
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(ContentBlock $contentBlock, array $modelData): ContentBlock
    {
        switch ($modelData[ContentBlockStateEnum::READY->value]) {
            case ContentBlockStateEnum::READY->value:
                $modelData['ready_at'] = now();
                break;
            case ContentBlockStateEnum::LIVE->value:
                $modelData['live_at'] = now();
                break;
            case ContentBlockStateEnum::RETIRED->value:
                $modelData['retired_at'] = now();
                break;
        }

        $this->update($contentBlock, $modelData, ['data','layout']);

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
            'state' => ['sometimes', 'required', Rule::in(ContentBlockStateEnum::values())]
        ];
    }

    public function asController(ContentBlock $contentBlock, $state): ContentBlock
    {
        $this->setRawAttributes([
            'state' => $state
        ]);

        return $this->handle($contentBlock, $this->validateAttributes());
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
