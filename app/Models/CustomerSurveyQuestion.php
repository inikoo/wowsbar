<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerSurveyQuestion
 *
 * @property int $id
 * @property int $customer_id
 * @property int $survey_question_id
 * @property string $answer_type
 * @property string $answer Can be string or survey question option id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereAnswerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereSurveyQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSurveyQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerSurveyQuestion extends Model
{
    use HasFactory;
}
