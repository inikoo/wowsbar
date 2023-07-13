<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Actions\Utils\Abbreviate;
use App\Actions\Utils\ReadableRandomStringGenerator;
use App\Models\Web\WebBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\ContentBlock
 *
 * @property int $id
 * @property int $web_block_type_id
 * @property int $web_block_id
 * @property int $tenant_id
 * @property mixed $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read WebBlock $webBlock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Website> $website
 * @property-read int|null $website_count
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereWebBlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock whereWebBlockTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlock withoutTrashed()
 * @mixin \Eloquent
 */

class ContentBlock extends Model
{
    use SoftDeletes;
    use HasSlug;

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

    public function website(): BelongsToMany
    {
        return $this->belongsToMany(Website::class)->using(ContentBlockWebsite::class)
            ->withTimestamps();
    }

}
