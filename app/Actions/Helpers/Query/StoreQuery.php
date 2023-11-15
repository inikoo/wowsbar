<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Actions\Market\Shop\Hydrators\ShopHydrateQueries;
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
        /** @var \App\Models\Helpers\Query $query */
        $query= Query::create($modelData);

        if($query->scope_type=='Shop') {
            ShopHydrateQueries::dispatch($query->scope);
        }

        return $query;
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
            'name'        => ['required', 'string','max"255'],
            'model_type'  => ['required', 'string'],
            'constrains'  => ['required', 'array'],
            'is_seeded'   => ['required', 'boolean']
        ];
    }

    public function asController(ActionRequest $request): Query
    {
        $request->validate();
        return $this->handle($request->validated());
    }
}
