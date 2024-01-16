<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shipper
 *
 * @property int $id
 * @property string $slug
 * @property string|null $name
 * @property int $country_id
 * @property int|null $provider_id
 * @property string|null $provider_type
 * @property array $data
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereProviderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper whereSlug($value)
 * @mixin \Eloquent
 */
class Shipper extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts   = [
        'data' => 'array',
    ];
}
