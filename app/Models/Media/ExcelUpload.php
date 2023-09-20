<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExcelUpload
 *
 * @property int $id
 * @property int|null $tenant_id
 * @property string $type
 * @property string $original_filename
 * @property string $filename
 * @property string $path
 * @property int $number_rows
 * @property string $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereNumberRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUpload whereUploadedAt($value)
 * @mixin \Eloquent
 */
class ExcelUpload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullPath(): string
    {
        return $this->path . '/' . $this->filename;
    }
}
