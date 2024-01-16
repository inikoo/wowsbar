<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShipperProvider
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShipperProvider extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected $casts   = [
        'data' => 'array'
    ];
}
