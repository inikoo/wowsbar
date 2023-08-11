<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use App\Enums\Portfolio\ContentBlockComponent\ContentBlockComponentTypeEnum;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Portfolio\ContentBlockComponent
 *
 * @property int $id
 * @property string|null $ulid
 * @property ContentBlockComponentTypeEnum $type
 * @property int $tenant_id
 * @property int $content_block_id
 * @property bool $visibility
 * @property array|null $layout
 * @property int|null $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Portfolio\ContentBlock $contentBlock
 * @property-read Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @method static Builder|ContentBlockComponent newModelQuery()
 * @method static Builder|ContentBlockComponent newQuery()
 * @method static Builder|ContentBlockComponent query()
 * @method static Builder|ContentBlockComponent whereContentBlockId($value)
 * @method static Builder|ContentBlockComponent whereCreatedAt($value)
 * @method static Builder|ContentBlockComponent whereDeletedAt($value)
 * @method static Builder|ContentBlockComponent whereId($value)
 * @method static Builder|ContentBlockComponent whereImageId($value)
 * @method static Builder|ContentBlockComponent whereLayout($value)
 * @method static Builder|ContentBlockComponent whereTenantId($value)
 * @method static Builder|ContentBlockComponent whereType($value)
 * @method static Builder|ContentBlockComponent whereUlid($value)
 * @method static Builder|ContentBlockComponent whereUpdatedAt($value)
 * @method static Builder|ContentBlockComponent whereVisibility($value)
 * @mixin \Eloquent
 */
class ContentBlockComponent extends Model implements HasMedia
{
    use BelongsToTenant;
    use InteractsWithMedia;


    protected $casts = [
        'layout'   => 'array',
        'type'     => ContentBlockComponentTypeEnum::class
    ];

    protected $attributes = [
        'layout'   => '{}',
    ];

    protected $guarded=[];

    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(ContentBlock::class);
    }

    public function image():BelongsTo
    {
        return $this->belongsTo(Media::class,'image_id');
    }

}
