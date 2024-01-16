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
 * @property int $id
 * @property string $slug
 * @property string|null $label
 * @property int $shipper_id
 * @property int $customer_id
 * @property array $credentials
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property-read Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $shipments
 * @property-read int|null $shipments_count
 * @property-read \App\Models\Shipper $shipper
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereShipperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipperAccount whereUpdatedAt($value)
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
