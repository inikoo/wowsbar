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
