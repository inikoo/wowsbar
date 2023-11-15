<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tag;

use App\Http\Resources\Tag\TagResource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\Tags\Tag;

class StoreTag
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(array $modelData): Tag
    {
        return Tag::create($modelData);
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
        $this->set('type', 'crm');
        $request->validate();

        return $this->handle($request->validated());
    }


}
