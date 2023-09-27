<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 18:45:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ExcelUploadRecord
 *
 * @property int $id
 * @property int $excel_upload_id
 * @property mixed $data
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Helpers\Upload|null $excel
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereExcelUploadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class UploadRecord extends Model
{
    protected $casts = [
        'values'        => 'array',
        'errors'        => 'array',


    ];

    protected $attributes = [
        'values'     => '{}',
        'errors'     => '{}',
    ];

    protected $guarded = [];

    public function excel(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }
}
