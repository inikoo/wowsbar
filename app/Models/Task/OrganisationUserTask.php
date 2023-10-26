<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\OrganisationUserTask
 *
 * @property int $id
 * @property int $organisation_user_id
 * @property int $task_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask whereOrganisationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationUserTask whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationUserTask extends Model
{
    use HasFactory;
}
