<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:02:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Http\Resources\Portfolio\SlideResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\Snapshot
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $user_type
 * @property int|null $user_id
 * @property string|null $parent_type
 * @property int|null $parent_id
 * @property int $customer_id
 * @property SnapshotStateEnum $state
 * @property string|null $published_at
 * @property string|null $published_until
 * @property string $checksum
 * @property array $layout
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read Model|\Eloquent $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Slide> $slides
 * @property-read int|null $slides_count
 * @method static Builder|Snapshot newModelQuery()
 * @method static Builder|Snapshot newQuery()
 * @method static Builder|Snapshot query()
 * @method static Builder|Snapshot whereChecksum($value)
 * @method static Builder|Snapshot whereComment($value)
 * @method static Builder|Snapshot whereCreatedAt($value)
 * @method static Builder|Snapshot whereCustomerId($value)
 * @method static Builder|Snapshot whereId($value)
 * @method static Builder|Snapshot whereLayout($value)
 * @method static Builder|Snapshot whereParentId($value)
 * @method static Builder|Snapshot whereParentType($value)
 * @method static Builder|Snapshot wherePublishedAt($value)
 * @method static Builder|Snapshot wherePublishedUntil($value)
 * @method static Builder|Snapshot whereSlug($value)
 * @method static Builder|Snapshot whereState($value)
 * @method static Builder|Snapshot whereUpdatedAt($value)
 * @method static Builder|Snapshot whereUserId($value)
 * @method static Builder|Snapshot whereUserType($value)
 * @mixin \Eloquent
 */
class Snapshot extends Model
{
    use HasSlug;
    use BelongsToCustomer;

    protected $dateFormat  = 'Y-m-d H:i:s P';
    protected array $dates = ['published_at', 'published_until'];

    protected $casts = [
        'layout'           => 'array',
        'state'            => SnapshotStateEnum::class
    ];

    protected $attributes = [
        'layout' => '{}'
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return $this->parent->slug.'-'.now()->isoFormat('YYMMDD');
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnCreate()
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function parent(): MorphTo
    {
        return $this->morphTo();
    }


    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function compiledLayout(): array
    {

        $slides=$this->slides()->where('visibility', true)->get();

        $compiledLayout = $this->layout;
        data_set($compiledLayout, 'components', json_decode(SlideResource::collection($slides)->toJson(), true));

        return $compiledLayout;
    }



}
