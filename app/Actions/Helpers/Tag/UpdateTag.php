<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 12:42:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Tag;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Helpers\Tag;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateTag
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;
    private Shop $parent;


    public function handle(Tag $tag, array $modelData): Tag
    {
        return $this->update($tag, $modelData);
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
            'type' => ['required', 'string'],
        ];
    }

    public function inProspect(Tag $tag, ActionRequest $request): Tag
    {
        $this->fillFromRequest($request);
        $this->fill(['type' => 'crm']);

        return $this->handle($tag, $this->validateAttributes());
    }

    public function inShop(Shop $shop, Tag $tag, ActionRequest $request): Tag
    {
        $this->parent = $shop;
        $this->fillFromRequest($request);
        $this->fill(['type' => 'crm']);

        return $this->handle($tag, $this->validateAttributes());
    }
}
