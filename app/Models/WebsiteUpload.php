<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebsiteUpload
 *
 * @property int $id
 * @property int $tenant_id
 * @property string $original_filename
 * @property string $filename
 * @property string $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteUpload whereUploadedAt($value)
 * @mixin \Eloquent
 */
class WebsiteUpload extends Model
{
    use HasFactory;

    protected $guarded = [];
}
