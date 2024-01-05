<?php

namespace App\Models;

use App\Enums\CRM\Shipping\ShippingStateEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts   = [
        'data' => 'array',
        'credentials' => 'array',
        'tracking' => 'array',
        'status' => ShippingStateEnum::class
    ];
}
