<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Auth\PublicUserStats
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CRM\PublicUser|null $publicUser
 * @method static Builder|PublicUserStats newModelQuery()
 * @method static Builder|PublicUserStats newQuery()
 * @method static Builder|PublicUserStats query()
 * @method static Builder|PublicUserStats whereCreatedAt($value)
 * @method static Builder|PublicUserStats whereId($value)
 * @method static Builder|PublicUserStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class PublicUserStats extends Model
{
    use HasFactory;

    protected $table = 'public_user_stats';

    protected $guarded = [];

    public function publicUser(): BelongsTo
    {
        return $this->belongsTo(PublicUser::class);
    }
}
