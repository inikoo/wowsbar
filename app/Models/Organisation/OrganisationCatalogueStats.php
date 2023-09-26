<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 09:46:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationCatalogueStats
 *
 * @property int $id
 * @property int $organisation_id
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
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberDepartments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberDepartmentsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberDepartmentsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberDepartmentsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberDepartmentsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberProductsStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberProductsStateDiscontinued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberProductsStateDiscontinuing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereNumberProductsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCatalogueStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationCatalogueStats extends Model
{
    protected $table = 'organisation_catalogue_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
