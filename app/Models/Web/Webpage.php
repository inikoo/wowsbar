<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Enums\Portfolio\Webpage\WebpageStatusEnum;
use App\Http\Resources\Web\WebpageBlocksResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Webpage extends Model
{
    use HasSlug;

    protected $casts = [
        'data'             => 'array',
        'settings'         => 'array',
        'compiled_layout' => 'array',
        'type'             => WebpageTypeEnum::class,
        'purpose'          => WebpagePurposeEnum::class,
        'state'            => WebpageStateEnum::class,

    ];

    protected $attributes = [
        'data'             => '{}',
        'settings'         => '{}',
        'compiled_layout' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageStats::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }
    public function unpublishedSnapshot(): BelongsTo
    {
        return $this->hasMany(WebpageVariant::class);
    }

    public function getCompiledContent(): array
    {
        data_set($compiled, 'blocks', WebpageBlocksResource::make($this->blocks)->getArray());

        return $compiled;
    }
}
