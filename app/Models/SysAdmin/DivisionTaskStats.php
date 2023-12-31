<?php

namespace App\Models\SysAdmin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organisation\DivisionTaskStats
 *
 * @property int $id
 * @property int $division_id
 * @property int $number_task_types
 * @property int $number_task_types_division_seo
 * @property int $number_task_types_division_ppc
 * @property int $number_task_types_division_social
 * @property int $number_task_types_division_prospects
 * @property int $number_task_types_division_banners
 * @property int $number_tasks
 * @property int $number_tasks_division_seo
 * @property int $number_tasks_division_ppc
 * @property int $number_tasks_division_social
 * @property int $number_tasks_division_prospects
 * @property int $number_tasks_division_banners
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypesDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypesDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypesDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypesDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTaskTypesDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasksDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasksDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasksDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasksDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereNumberTasksDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionTaskStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DivisionTaskStats extends Model
{
    protected $table   = 'division_task_stats';
    protected $guarded = [];
}
