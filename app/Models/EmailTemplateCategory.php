<?php

namespace App\Models;

use App\Models\Mail\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\EmailTemplateCategory
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EmailTemplate> $templates
 * @property-read int|null $templates_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplateCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailTemplateCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(EmailTemplate::class, EmailTemplatePivotEmailCategory::class);
    }
}
