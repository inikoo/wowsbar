<?php

namespace App\Models\HumanResources;

use App\Models\Media\ExcelUploadRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebsiteUploadRecord
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $upload_id
 * @property mixed $data
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelUploadRecord whereWebsiteUploadId($value)
 * @mixin \Eloquent
 */

class EmployeeUploadRecord extends Model
{
    use HasFactory;

    protected $guarded = [];
}
