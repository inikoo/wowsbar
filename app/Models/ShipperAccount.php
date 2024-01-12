<?php

namespace App\Models;

use App\Models\CRM\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\ShipperAccount
 *
 * @property-read Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $shipments
 * @property-read int|null $shipments_count
 * @property-read \App\Models\Shipper|null $shipper
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount query()
 * @mixin \Eloquent
 */

class ShipperAccount extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];
    protected $casts   = [
        'credentials' => 'array',
        'data'        => 'array'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('label')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(12)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function shipper(): BelongsTo
    {
        return $this->belongsTo(Shipper::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
