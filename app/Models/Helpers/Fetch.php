<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 16 Nov 2023 12:38:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use App\Enums\Helpers\Fetch\FetchTypeEnum;
use App\Models\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Helpers\Fetch
 *
 * @property int $id
 * @property string $slug
 * @property FetchTypeEnum $type
 * @property int $number_items
 * @property int $number_no_changes
 * @property int $number_updates
 * @property int $number_stores
 * @property int $number_errors
 * @property string|null $finished_at
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereNumberErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereNumberItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereNumberNoChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereNumberStores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereNumberUpdates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fetch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Fetch extends Model implements Auditable
{
    use HasSlug;
    use HasHistory;

    protected $casts = [
        'data'     => 'array',
        'type'     => FetchTypeEnum::class,
    ];

    protected $attributes = [
        'data'     => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected array $auditInclude = [
        'finished_at',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return  now()->format('Y-m-d').' '.$this->type->value;
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(24);
    }

}
