<?php
/*
 * author Arya Permana - Kirin
 * created on 18-10-2024-16h-33m
 * github: https://github.com/KirinZero0
 * copyright 2024
*/

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Web\ExternalLink
 *
 * @property int $id
 * @property string $url
 * @property int $number_websites_shown
 * @property int $number_webpages_shown
 * @property int $number_web_blocks_shown
 * @property int $number_websites_hidden
 * @property int $number_webpages_hidden
 * @property int $number_web_blocks_hidden
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\WebBlock> $webBlocks
 * @property-read int|null $web_blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\Webpage> $webpages
 * @property-read int|null $webpages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\Website> $websites
 * @property-read int|null $websites_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebBlocksHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebBlocksShown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebpagesHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebpagesShown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebsitesHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereNumberWebsitesShown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalLink whereUrl($value)
 * @mixin \Eloquent
 */
class ExternalLink extends Model
{
    protected $casts = [
    ];

    protected $attributes = [
    ];

    protected $guarded = [];

    public function webBlocks()
    {
        return $this->belongsToMany(WebBlock::class, 'web_block_has_external_link')
                    ->withPivot('website_id', 'webpage_id', 'show')
                    ->withTimestamps();
    }

    public function webpages()
    {
        return $this->belongsToMany(Webpage::class, 'web_block_has_external_link')
                    ->withPivot('website_id', 'web_block_id', 'show')
                    ->withTimestamps();
    }

    public function websites()
    {
        return $this->belongsToMany(Website::class, 'web_block_has_external_link')
                    ->withPivot('webpage_id', 'web_block_id', 'show')
                    ->withTimestamps();
    }
}
