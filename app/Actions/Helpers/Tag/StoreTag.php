<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 12:42:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Tag;

use App\Http\Resources\Tag\TagResource;
use App\Models\Helpers\Tag;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTag
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(array $modelData): Tag
    {
        /** @var Tag $tag */
        $tag=  Tag::findOrCreate($modelData['name'], $modelData['type']);
        if($tag->type=='crm') {
            $tag->crmStats()->create();
        }

        return $tag;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.prospects.edit");
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'string']
        ];
    }

    public function jsonResponse(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }


    public function inProspect(ActionRequest $request): Tag
    {

        $this->fillFromRequest($request);
        $this->fill(['type' => 'crm']);

        return $this->handle($this->validateAttributes());
    }


}
