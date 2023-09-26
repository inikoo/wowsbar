<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:53:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\ProductCategory;

use App\Actions\Catalogue\ProductCategory\Hydrators\ProductCategoryHydrateUniversalSearch;

use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateDepartments;
use App\Enums\Catalogue\ProductCategory\ProductCategoryTypeEnum;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Catalogue\ProductCategory;
use App\Rules\CaseSensitive;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProductCategory
{
    use AsAction;
    use WithAttributes;

    private int $hydratorsDelay     = 0;
    public string $commandSignature = 'product-category:new {code} {name}';

    public function handle(Organisation|ProductCategory $parent, array $modelData): ProductCategory
    {
        if (class_basename($parent) == 'ProductCategory') {
            $modelData['type'] = ProductCategoryTypeEnum::BRANCH;
        } else {
            $modelData['type'] = ProductCategoryTypeEnum::ROOT;
        }

        /** @var ProductCategory $productCategory */
        $productCategory = $parent->departments()->create($modelData);

        $productCategory->stats()->create();
        $productCategory->salesStats()->create([
            'scope' => 'sales'
        ]);


        ProductCategoryHydrateUniversalSearch::dispatch($productCategory);
        OrganisationHydrateDepartments::dispatch(organisation());

        return $productCategory;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'unique:product_categories', 'between:2,9', 'alpha_dash', new CaseSensitive('product_categories')],
            'name'        => ['required', 'max:250', 'string'],
            'image_id'    => ['sometimes', 'required', 'exists:media,id'],
            'state'       => ['sometimes', 'required'],
            'description' => ['sometimes', 'required', 'max:1500'],
        ];
    }

    public function action(Shop|ProductCategory $parent, array $objectData): ProductCategory
    {
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }

    public function asCommand(Command $command): int
    {
        $this->handle(organisation(), [
            'code' => $command->argument('code'),
            'name' => $command->argument('name')
        ]);

        echo $command->argument('name') . " added \n";

        return 0;
    }
}
