<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tag;

use App\Models\Leads\Prospect;
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
        return Tag::create(['name' => $modelData['name']]);
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
            'name' => ['required', 'string']
        ];
    }

    public function asController(ActionRequest $request): Tag
    {
        $request->validate();

        return $this->handle($request->validated());
    }
}
