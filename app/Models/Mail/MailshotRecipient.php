<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:32:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Mail\MailshotRecipient
 *
 * @property int $id
 * @property int $mailshot_id
 * @property int $email_delivery_id
 * @property string $recipient_type
 * @property int $recipient_id
 * @property int $channel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \App\Models\Mail\EmailDelivery|null $emailDelivery
 * @property-read \App\Models\Mail\Mailshot $mailshot
 * @property-read Model|\Eloquent $recipient
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereEmailDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereMailshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailshotRecipient extends Model
{
    protected $guarded = [];

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function mailshot(): BelongsTo
    {
        return $this->belongsTo(Mailshot::class);
    }

    public function emailDelivery(): BelongsTo
    {
        return $this->belongsTo(EmailDelivery::class);
    }
}
