<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Enums\HumanResources\ClockingMachine\ClockingMachineTypeEnum;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\ClockingMachine
 *
 * @property int $id
 * @property int $workplace_id
 * @property string $slug
 * @property string $name
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property ClockingMachineTypeEnum $type
 * @property-read Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, \App\Models\HumanResources\Clocking> $clockings
 * @property-read int|null $clockings_count
 * @property-read UniversalSearch|null $universalSearch
 * @property-read \App\Models\HumanResources\Workplace $workplace
 * @method static Builder|ClockingMachine newModelQuery()
 * @method static Builder|ClockingMachine newQuery()
 * @method static Builder|ClockingMachine onlyTrashed()
 * @method static Builder|ClockingMachine query()
 * @method static Builder|ClockingMachine whereCreatedAt($value)
 * @method static Builder|ClockingMachine whereData($value)
 * @method static Builder|ClockingMachine whereDeletedAt($value)
 * @method static Builder|ClockingMachine whereId($value)
 * @method static Builder|ClockingMachine whereName($value)
 * @method static Builder|ClockingMachine whereSlug($value)
 * @method static Builder|ClockingMachine whereType($value)
 * @method static Builder|ClockingMachine whereUpdatedAt($value)
 * @method static Builder|ClockingMachine whereWorkplaceId($value)
 * @method static Builder|ClockingMachine withTrashed()
 * @method static Builder|ClockingMachine withoutTrashed()
 * @mixin Eloquent
 */
class ClockingMachine extends Model implements Auditable
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;
    use HasHistory;

    protected $casts = [
        'data'   => 'array',
        'status' => 'boolean',
        'type'   => ClockingMachineTypeEnum::class
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function generateTags(): array
    {
        return [
            'sysadmin'
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(8);
    }

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }

    public function clockings(): HasMany
    {
        return $this->hasMany(Clocking::class);
    }
}
