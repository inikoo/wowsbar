<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\MailshotRecipient
 *
 * @property int $id
 * @property int $mailshot_id
 * @property string $recipient_type
 * @property int $recipient_id
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
}
