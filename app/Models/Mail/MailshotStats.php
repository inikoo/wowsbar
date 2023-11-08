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
 * @property int $number_email_deliveries
 * @property int $number_email_deliveries_state_ready
 * @property int $number_email_deliveries_state_error
 * @property int $number_email_deliveries_state_sent
 * @property int $number_email_deliveries_state_hard_bounce
 * @property int $number_email_deliveries_state_soft_bounce
 * @property int $number_email_deliveries_state_opened
 * @property int $number_email_deliveries_state_clicked
 * @property int $number_email_deliveries_state_spam
 * @property int $number_email_deliveries_state_unsubscribed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereMailshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateClicked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateHardBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateSoftBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateSpam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereNumberEmailDeliveriesStateUnsubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailshotStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailshotStats extends Model
{
    protected $table = 'mailshot_stats';

    protected $guarded = [];


}
