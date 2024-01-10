<?php

namespace App\Models;

use App\Enums\CRM\Shipping\ShippingStateEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $status
 * @property string $reference
 * @property string $tracking
 * @property string $error_message
 * @property string $min_state
 * @property string $max_state
 * @property string $tracked_at
 * @property string $tracked_count
 * @property string $data
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
