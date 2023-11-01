<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:32:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use App\Actions\Utils\Abbreviate;
use App\Enums\Mail\MailshotStateEnum;
use App\Enums\Mail\MailshotTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Mail\Mailshot
 *
 * @property int $id
 * @property string $slug
 * @property string $subject
 * @property MailshotStateEnum $state
 * @property string|null $schedule_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property MailshotTypeEnum $type
 * @property string $date
 * @property string|null $ready_at
 * @property string|null $start_sending_at
 * @property string|null $sent_at
 * @property string|null $cancelled_at
 * @property string|null $stopped_at
 * @property array $layout
 * @property array $recipients
 * @property int|null $publisher_id org user
 * @property string $scope_type
 * @property int $scope_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot wherePublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereReadyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereScheduleAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereScopeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereScopeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereStartSendingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereStoppedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailshot withoutTrashed()
 * @mixin \Eloquent
 */
class Mailshot extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $casts = [
        'recipients' => 'array',
        'layout'     => 'array',
        'type'       => MailshotTypeEnum::class,
        'state'      => MailshotStateEnum::class

    ];

    protected $attributes = [
        'layout'     => '{}',
        'recipients' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Abbreviate::run(string: $this->subject).' '.Abbreviate::run($this->type->value);
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16);
    }

    public function scope(): MorphTo
    {
        return $this->morphTo();
    }


}
