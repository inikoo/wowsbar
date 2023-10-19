<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property string $task_name
 * @property string $portfolio_social_account_id
 * @property string $start_at
 * @property string $end_at
 * @property string $duration
 * @property string $type
 * @property string $status
 * @property string $description
 * @property string $notes
 * @property \App\Models\Portfolio\PortfolioSocialAccount $platform
 * @mixin \Eloquent
 */

class PortfolioSocialAccountPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(PortfolioSocialAccount::class, 'portfolio_social_account_id');
    }
}
