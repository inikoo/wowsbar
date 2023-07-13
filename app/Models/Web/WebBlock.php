<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:29:01 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Web\WebBlock\WebBlockScopeEnum;
use App\Models\Portfolio\ContentBlock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Web\WebBlock
 *
 * @property int $id
 * @property string $slug
 * @property WebBlockScopeEnum $scope
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property int $web_block_type_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ContentBlock> $contentBlocks
 * @property-read int|null $content_blocks_count
 * @property-read \App\Models\Web\WebBlockStats|null $stats
 * @property-read \App\Models\Web\WebBlockType $webBlockType
 * @method static Builder|WebBlock newModelQuery()
 * @method static Builder|WebBlock newQuery()
 * @method static Builder|WebBlock onlyTrashed()
 * @method static Builder|WebBlock query()
 * @method static Builder|WebBlock whereCode($value)
 * @method static Builder|WebBlock whereCreatedAt($value)
 * @method static Builder|WebBlock whereData($value)
 * @method static Builder|WebBlock whereDeletedAt($value)
 * @method static Builder|WebBlock whereDescription($value)
 * @method static Builder|WebBlock whereId($value)
 * @method static Builder|WebBlock whereName($value)
 * @method static Builder|WebBlock whereScope($value)
 * @method static Builder|WebBlock whereSlug($value)
 * @method static Builder|WebBlock whereUpdatedAt($value)
 * @method static Builder|WebBlock whereWebBlockTypeId($value)
 * @method static Builder|WebBlock withTrashed()
 * @method static Builder|WebBlock withoutTrashed()
 * @mixin \Eloquent
 */
class WebBlock extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $casts = [
        'data'  => 'array',
        'scope' => WebBlockScopeEnum::class,

    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function webBlockType(): BelongsTo
    {
        return $this->belongsTo(WebBlockType::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebBlockStats::class);
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class);
    }


}
