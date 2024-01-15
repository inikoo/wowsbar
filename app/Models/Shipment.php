<?php

namespace App\Models;

use App\Enums\CRM\Shipping\ShippingStateEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shipment
 *
 * @property ShippingStateEnum $status
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 * @mixin \Eloquent
 */

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts   = [
        'data'        => 'array',
        'credentials' => 'array',
        'tracking'    => 'array',
        'status'      => ShippingStateEnum::class
    ];
}
