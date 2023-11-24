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
    use WithQueryCompiler;

    private bool $asAction = false;

    /**
     * @throws \Exception
     */
    public function handle(array $modelData): Query
    {
        data_set($modelData, 'compiled_constrains', $this->compileConstrains($modelData['constrains']));
        //dd($modelData);
        /** @var \App\Models\Helpers\Query $query */
        $query = Query::create($modelData);
        if ($query->scope_type == 'Shop') {
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
            'name'       => ['required', 'string', 'max:255'],
            'scope_type' => ['required', 'string'],
            'model_type' => ['required', 'string'],
            'scope_id'   => ['required', 'integer'],
            'constrains' => ['required', 'array'],
            'is_seeded'  => ['sometimes', 'boolean'],
            'slug'       => ['sometimes', 'string'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function action(array $objectData): Query
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }

    /**
     * @throws \Exception
     */
    public function asController(ActionRequest $request): Query
    {
        $request->validate();

        return $this->handle($request->validated());
    }
}
