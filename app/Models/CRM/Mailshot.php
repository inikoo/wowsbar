<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\Mailshot
 *
 * @property int $id
 * @property string $slug
 * @property string $subject
 * @property int $email_template_id
 * @property string $state
 * @property string|null $schedule_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereEmailTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereScheduleAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mailshot extends Model
{
    use HasFactory;
}
