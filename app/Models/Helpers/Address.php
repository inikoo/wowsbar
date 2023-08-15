<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 26 Aug 2022 01:45:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Models\Helpers;

use App\Models\Assets\Country;
use App\Models\Traits\IsAddress;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * App\Models\Helpers\Address
 *
 * @property int $id
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $sorting_code
 * @property string|null $postal_code
 * @property string|null $locality
 * @property string|null $dependant_locality
 * @property string|null $administrative_area
 * @property string|null $country_code
 * @property int|null $country_id
 * @property string|null $checksum
 * @property bool $historic
 * @property int $usage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Country|null $country
 * @property-read string $formatted_address
 * @property-read Model|\Eloquent $owner
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereAddressLine1($value)
 * @method static Builder|Address whereAddressLine2($value)
 * @method static Builder|Address whereAdministrativeArea($value)
 * @method static Builder|Address whereChecksum($value)
 * @method static Builder|Address whereCountryCode($value)
 * @method static Builder|Address whereCountryId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereDependantLocality($value)
 * @method static Builder|Address whereHistoric($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereLocality($value)
 * @method static Builder|Address wherePostalCode($value)
 * @method static Builder|Address whereSortingCode($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static Builder|Address whereUsage($value)
 * @mixin Eloquent
 */
class Address extends Model
{
    use UsesTenantConnection;
    use HasFactory;
    use IsAddress;

    protected $table ='addresses';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::created(
            function (Address $address) {
                if ($country = (new Country())->firstWhere('id', $address->country_id)) {
                    $address->country_code = $country->code;
                    $address->save();
                }
            }
        );
    }
}
