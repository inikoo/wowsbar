<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Fri, 10 Mar 2023 11:05:41 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Actions\Catalogue\ProductCategory\Hydrators;

use App\Models\Catalogue\ProductCategory;
use Lorisleiva\Actions\Concerns\AsAction;

class ProductCategoryHydrateUniversalSearch
{
    use AsAction;

    public function handle(ProductCategory $productCategory): void
    {
        $productCategory->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation'=> true,
                'section'        => 'catalogue',
                'title'          => $productCategory->name,
                'description'    => $productCategory->code
            ]
        );
    }

}
