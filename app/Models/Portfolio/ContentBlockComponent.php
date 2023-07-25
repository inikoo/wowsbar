<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use App\Enums\Portfolio\ContentBlockComponent\ContentBlockComponentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Portfolio\ContentBlockComponent
 *
 * @property int $id
 * @property ContentBlockComponentTypeEnum $type
 * @property int $tenant_id
 * @property int $content_block_id
 * @property array $layout
 * @property int|null $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Portfolio\ContentBlock $contentBlock
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereContentBlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockComponent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContentBlockComponent extends Model implements HasMedia
{

    use BelongsToTenant;
    use InteractsWithMedia;


    protected $casts = [
        'layout'   => 'array',
        'type'=>ContentBlockComponentTypeEnum::class
    ];

    protected $attributes = [
        'layout'   => '{}',
    ];

    protected $guarded=[];
    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(ContentBlock::class);
    }


}
