<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\EmailTemplate
 *
 * @property int $id
 * @property string $title
 * @property mixed $data
 * @property mixed $compiled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCompiled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailTemplate extends Model
{
    use HasFactory;
}
