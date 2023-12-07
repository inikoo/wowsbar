<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailTemplatePivotEmailCategory
 *
 * @property int $id
 * @property int $email_template_id
 * @property int $email_template_category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory whereEmailTemplateCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory whereEmailTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplatePivotEmailCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailTemplatePivotEmailCategory extends Model
{
    use HasFactory;
}
