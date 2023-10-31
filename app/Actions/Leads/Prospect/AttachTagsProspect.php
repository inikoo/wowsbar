<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Models\Leads\Prospect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class AttachTagsProspect
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Prospect $prospect, array $modelData): Prospect
    {
        $prospect->attachTags($modelData['tags']);

        return $prospect;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.prospects.edit");
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'tags' => ['required', 'array']
        ];
    }

    public function asController(Prospect $prospect, ActionRequest $request): Prospect
    {
        $request->validate();

        return $this->handle($prospect, $request->validated());
    }
}
