<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\SocialPost
 *
 * @property int $id
 * @property int $task_id
 * @property string $slug
 * @property string $start_at
 * @property string|null $end_at
 * @property int $duration days
 * @property int $portfolio_social_account_id
 * @property string $type
 * @property string $status
 * @property string|null $description
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \App\Models\Portfolio\PortfolioSocialAccount $platform
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost wherePortfolioSocialAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost withoutTrashed()
 * @mixin \Eloquent
 */

class SocialPost extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $guarded = [];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(PortfolioSocialAccount::class, 'portfolio_social_account_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('task_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
