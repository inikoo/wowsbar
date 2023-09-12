<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebsiteUploadRecord
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $website_upload_id
 * @property mixed $data
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUploadRecord whereWebsiteUploadId($value)
 * @mixin \Eloquent
 */

class WebsiteUploadRecord extends Model
{
    use HasFactory;

    protected $guarded = [];
}
