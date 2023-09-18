<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Portfolio\Slide
 *
 * @property int $id
 * @property string|null $ulid
 * @property int $customer_id
 * @property int $snapshot_id
 * @property bool $visibility
 * @property array|null $layout
 * @property int|null $image_id
 * @property int|null $mobile_image_id
 * @property int|null $tablet_image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read Media|null $image
 * @property-read Media|null $imageMobile
 * @property-read Media|null $imageTablet
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Portfolio\Snapshot $snapshot
 * @method static Builder|Slide newModelQuery()
 * @method static Builder|Slide newQuery()
 * @method static Builder|Slide query()
 * @method static Builder|Slide whereCreatedAt($value)
 * @method static Builder|Slide whereCustomerId($value)
 * @method static Builder|Slide whereDeletedAt($value)
 * @method static Builder|Slide whereId($value)
 * @method static Builder|Slide whereImageId($value)
 * @method static Builder|Slide whereLayout($value)
 * @method static Builder|Slide whereMobileImageId($value)
 * @method static Builder|Slide whereSnapshotId($value)
 * @method static Builder|Slide whereTabletImageId($value)
 * @method static Builder|Slide whereUlid($value)
 * @method static Builder|Slide whereUpdatedAt($value)
 * @method static Builder|Slide whereVisibility($value)
 * @mixin \Eloquent
 */
class Slide extends Model implements HasMedia
{
    use BelongsToCustomer;
    use InteractsWithMedia;


    protected $casts = [
        'layout'   => 'array',
    ];

    protected $attributes = [
        'layout'   => '{}',
    ];

    protected $guarded=[];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function imageMobile(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mobile_image_id');
    }

    public function imageTablet(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'tablet_image_id');
    }

}
