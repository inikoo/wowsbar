<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 09:46:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Market\ShopCatalogueStats
 *
 * @property int $id
 * @property int $shop_id
 * @property int $number_departments
 * @property int $number_departments_state_in_process
 * @property int $number_departments_state_active
 * @property int $number_departments_state_discontinuing
 * @property int $number_departments_state_discontinued
 * @property int $number_products
 * @property int $number_products_state_in_process
 * @property int $number_products_state_active
 * @property int $number_products_state_discontinuing
 * @property int $number_products_state_discontinued
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Market\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberDepartments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberDepartmentsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberDepartmentsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberDepartmentsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberDepartmentsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberProductsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberProductsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberProductsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereNumberProductsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopCatalogueStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopCatalogueStats extends Model
{
    protected $table = 'shop_catalogue_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
