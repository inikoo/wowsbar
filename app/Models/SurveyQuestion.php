<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SurveyQuestion
 *
 * @property int $id
 * @property string $question
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SurveyQuestion extends Model
{
    use HasFactory;
}
