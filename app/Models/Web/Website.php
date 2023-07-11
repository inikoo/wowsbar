<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:29:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Models\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Web\Website
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $domain
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Web\WebsiteStats|null $stats
 * @property-read Tenant $tenant
 * @method static \Illuminate\Database\Eloquent\Builder|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Website withoutTrashed()
 * @mixin \Eloquent
 */
class Website extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $casts = [
        'data'      => 'array',
    ];

    protected $attributes = [
        'data'      => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebsiteStats::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

}
