<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 18:45:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ExcelUpload
 *
 * @property int $id
 * @property int|null $organisation_user_id
 * @property string $type
 * @property string $original_filename
 * @property string $filename
 * @property string $path
 * @property int $number_rows
 * @property int $number_success
 * @property int $number_fails
 * @property string $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\UploadRecord> $records
 * @property-read int|null $records_count
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereNumberFails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereNumberRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereNumberSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOrganisationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUploadedAt($value)
 * @mixin \Eloquent
 */
class Upload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullPath(): string
    {
        return $this->path . '/' . $this->filename;
    }

    public function records(): HasMany
    {
        return $this->hasMany(UploadRecord::class);
    }
}
