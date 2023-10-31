<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Models\Helpers\Query;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreQuery
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(array $modelData): Query
    {
        return Query::create($modelData);
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
            'model_type' => ['required', 'string'],
            'base' => ['required', 'string'],
            'filters' => ['required', 'string'],
            'read_only' => ['required', 'string']
        ];
    }

    public function asController(ActionRequest $request): Query
    {
        $request->validate();

        return $this->handle($request->validated());
    }
}
