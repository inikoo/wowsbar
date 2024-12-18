<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:29:01 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Enums\Organisation\Web\WebBlockType\WebBlockTypeScopeEnum;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Web\WebBlockType
 *
 * @property int $id
 * @property string $slug
 * @property WebBlockTypeScopeEnum $scope
 * @property string $code
 * @property string $name
 * @property int|null $image_id
 * @property bool $fixed
 * @property string|null $description
 * @property array $blueprint
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Web\WebBlockTypeStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\WebBlock> $webBlocks
 * @property-read int|null $web_blocks_count
 * @method static Builder|WebBlockType newModelQuery()
 * @method static Builder|WebBlockType newQuery()
 * @method static Builder|WebBlockType query()
 * @method static Builder|WebBlockType whereBlueprint($value)
 * @method static Builder|WebBlockType whereCode($value)
 * @method static Builder|WebBlockType whereCreatedAt($value)
 * @method static Builder|WebBlockType whereData($value)
 * @method static Builder|WebBlockType whereDescription($value)
 * @method static Builder|WebBlockType whereFixed($value)
 * @method static Builder|WebBlockType whereId($value)
 * @method static Builder|WebBlockType whereImageId($value)
 * @method static Builder|WebBlockType whereName($value)
 * @method static Builder|WebBlockType whereScope($value)
 * @method static Builder|WebBlockType whereSlug($value)
 * @method static Builder|WebBlockType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WebBlockType extends Model implements HasMedia
{
    use HasSlug;
    use InteractsWithMedia;

    protected $casts = [
        'blueprint' => 'array',
        'data'      => 'array',
        'scope'     => WebBlockTypeScopeEnum::class,
    ];

    protected $attributes = [
        'blueprint' => '{}',
        'data'      => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebBlockTypeStats::class);
    }

    public function webBlocks(): HasMany
    {
        return $this->hasMany(WebBlock::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }

    public function imageSources($width = 0, $height = 0)
    {
        if ($this->image) {
            $avatarThumbnail = $this->image->getImage()->resize($width, $height);
            return GetPictureSources::run($avatarThumbnail);
        }
        return null;
    }
}
