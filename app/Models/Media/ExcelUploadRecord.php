<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property-read \App\Models\Media\ExcelUpload|null $excel
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereExcelUploadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class ExcelUploadRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function excel(): BelongsTo
    {
        return $this->belongsTo(ExcelUpload::class);
    }
}
