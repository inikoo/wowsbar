<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 14:36:10 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use App\Enums\Web\WebBlockType\WebBlockTypeScopeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Web\WebBlockType
 *
 * @property int $id
 * @property WebBlockTypeScopeEnum $scope
 * @property WebBlockTypeSlugEnum $slug
 * @property string $name
 * @property array $blueprint
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Web\WebBlockTypeStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\WebBlock> $webBlocks
 * @property-read int|null $web_blocks_count
 * @method static Builder|WebBlockType newModelQuery()
 * @method static Builder|WebBlockType newQuery()
 * @method static Builder|WebBlockType onlyTrashed()
 * @method static Builder|WebBlockType query()
 * @method static Builder|WebBlockType whereBlueprint($value)
 * @method static Builder|WebBlockType whereCreatedAt($value)
 * @method static Builder|WebBlockType whereData($value)
 * @method static Builder|WebBlockType whereDeletedAt($value)
 * @method static Builder|WebBlockType whereId($value)
 * @method static Builder|WebBlockType whereName($value)
 * @method static Builder|WebBlockType whereScope($value)
 * @method static Builder|WebBlockType whereSlug($value)
 * @method static Builder|WebBlockType whereUpdatedAt($value)
 * @method static Builder|WebBlockType withTrashed()
 * @method static Builder|WebBlockType withoutTrashed()
 * @mixin \Eloquent
 */
class WebBlockType extends Model
{
    use SoftDeletes;

    protected $casts = [
        'blueprint' => 'array',
        'data'      => 'array',
        'slug'      => WebBlockTypeSlugEnum::class,
        'scope'     => WebBlockTypeScopeEnum::class,
    ];

    protected $attributes = [
        'blueprint' => '{}',
        'data'      => '{}',
    ];

    protected $guarded = [];


    public function webBlocks(): HasMany
    {
        return $this->hasMany(WebBlock::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebBlockTypeStats::class);
    }

}
