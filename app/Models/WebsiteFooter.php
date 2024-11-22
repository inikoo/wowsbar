<?php

namespace App\Models;

use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WebsiteFooter
 *
 * @property int $id
 * @property int $website_id
 * @property int|null $unpublished_footer_snapshot_id
 * @property int|null $live_footer_snapshot_id
 * @property array $compiled_layout
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Webpage|null $webpage
 * @property-read Website $website
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereCompiledLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereLiveFooterSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereUnpublishedFooterSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteFooter whereWebsiteId($value)
 * @mixin \Eloquent
 */
class WebsiteFooter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'compiled_layout' => 'array'
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }
}
