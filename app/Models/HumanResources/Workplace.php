<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Actions\Utils\Abbreviate;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Models\Assets\Timezone;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasUniversalSearch;
use App\Models\WorkplaceStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\Workplace
 *
 * @property int $id
 * @property bool $status
 * @property WorkplaceTypeEnum $type
 * @property string $slug
 * @property string $name
 * @property int|null $timezone_id
 * @property int|null $address_id
 * @property array $data
 * @property array $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HumanResources\ClockingMachine> $clockingMachines
 * @property-read int|null $clocking_machines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HumanResources\Clocking> $clockings
 * @property-read int|null $clockings_count
 * @property-read \App\Models\Assets\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HumanResources\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read string $formatted_address
 * @property-read Model|\Eloquent $owner
 * @property-read WorkplaceStats|null $stats
 * @property-read Timezone|null $timezone
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace query()
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Workplace withoutTrashed()
 * @mixin \Eloquent
 */
class Workplace extends Model
{
    use HasSlug;
    use HasUniversalSearch;
    use SoftDeletes;
    use HasAddress;

    protected $casts = [
        'data'     => 'array',
        'location' => 'array',
        'status'   => 'boolean',
        'type'     => WorkplaceTypeEnum::class
    ];

    protected $attributes = [
        'data'     => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Abbreviate::run($this->name, digits: true, maximumLength: 8);
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(8);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo('owner');
    }

    public function clockingMachines(): HasMany
    {
        return $this->hasMany(ClockingMachine::class);
    }

    public function clockings(): HasMany
    {
        return $this->hasMany(Clocking::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WorkplaceStats::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

}
