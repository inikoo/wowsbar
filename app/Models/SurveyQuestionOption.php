<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SurveyQuestionOption
 *
 * @property int $id
 * @property int $survey_question_id
 * @property string $option
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption whereSurveyQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestionOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SurveyQuestionOption extends Model
{
    use HasFactory;
}
