<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 15:59:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mail\MailshotStats
 *
 * @property int $id
 * @property int $mailshot_id
 * @property int $number_estimated_dispatched_emails
 * @property string|null $estimated_dispatched_emails_calculated_at
 * @property int $number_dispatched_emails
 * @property int $number_dispatched_emails_state_ready
 * @property int $number_dispatched_emails_state_error
 * @property int $number_dispatched_emails_state_sent
 * @property int $number_dispatched_emails_state_hard_bounce
 * @property int $number_dispatched_emails_state_soft_bounce
 * @property int $number_dispatched_emails_state_opened
 * @property int $number_dispatched_emails_state_clicked
 * @property int $number_dispatched_emails_state_spam
 * @property int $number_dispatched_emails_state_unsubscribed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereEstimatedDispatchedEmailsCalculatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereMailshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateClicked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateHardBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateSoftBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateSpam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberDispatchedEmailsStateUnsubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEstimatedDispatchedEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailshotStats extends Model
{
    protected $table = 'mailshot_stats';

    protected $guarded = [];


}
