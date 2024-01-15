<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shipper
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipper query()
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
