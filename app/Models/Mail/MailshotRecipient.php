<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:32:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Mail\MailshotRecipient
 *
 * @property int $id
 * @property int $mailshot_id
 * @property string $recipient_type
 * @property int $recipient_id
 * @property \App\Models\Mail\Mailshot $mailshot
 * @property \App\Models\Leads\Prospect|\App\Models\CRM\Customer $recipient
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereMailshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotRecipient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailshotRecipient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function mailshot(): BelongsTo
    {
        return $this->belongsTo(Mailshot::class);
    }
}
