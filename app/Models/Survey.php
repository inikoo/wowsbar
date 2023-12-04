<?php

namespace App\Models;

use App\Models\Market\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Survey
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property int $shop_id
 * @property mixed $data
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property \App\Models\Market\Shop $shop
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Survey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey query()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Survey extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }
}
