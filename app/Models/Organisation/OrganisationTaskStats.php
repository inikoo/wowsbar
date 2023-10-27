<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 19:04:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organisation\OrganisationTaskStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_divisions
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
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberDivisions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypesDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypesDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypesDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypesDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTaskTypesDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasksDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasksDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasksDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasksDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereNumberTasksDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationTaskStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationTaskStats extends Model
{
    protected $table   = 'organisation_task_stats';
    protected $guarded = [];
}
