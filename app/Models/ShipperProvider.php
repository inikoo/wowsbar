<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShipperProvider
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperProvider query()
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
