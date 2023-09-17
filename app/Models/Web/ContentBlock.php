<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Actions\Utils\Abbreviate;
use App\Actions\Utils\ReadableRandomStringGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Web\ContentBlock
 *
 * @property-read \App\Models\Web\WebBlock|null $webBlock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\WebpageVariant> $webpageVariants
 * @property-read int|null $webpage_variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock withoutTrashed()
 * @mixin \Eloquent
 */
class ContentBlock extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasFactory;

    protected $casts = [
        'layout'=> 'array',
        'data'  => 'array',
    ];

    protected $attributes = [
        'layout' => '{}',
        'data'   => '{}',
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

                $webBlockSlug = $this->webBlock->slug;
                if ($webBlockSlug != '') {
                    return Abbreviate::run($webBlockSlug);
                }

                return ReadableRandomStringGenerator::run();
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }


    public function webBlock(): BelongsTo
    {
        return $this->belongsTo(WebBlock::class);
    }

    public function webpageVariants(): BelongsToMany
    {
        return $this->belongsToMany(WebpageVariant::class)->using(ContentBlockWebpageVariant::class)
            ->withTimestamps()->withPivot(['position']);
    }

}
