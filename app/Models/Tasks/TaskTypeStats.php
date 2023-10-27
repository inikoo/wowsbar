<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 11:00:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tasks\TaskTypeStats
 *
 * @property int $id
 * @property int $task_type_id
 * @property int $number_tasks
 * @property int $number_tasks_division_seo
 * @property int $number_tasks_division_ppc
 * @property int $number_tasks_division_social
 * @property int $number_tasks_division_prospects
 * @property int $number_tasks_division_banners
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasksDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasksDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasksDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasksDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereNumberTasksDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereTaskTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskTypeStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskTypeStats extends Model
{
    protected $table   = 'task_type_stats';

}
