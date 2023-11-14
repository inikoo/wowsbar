<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Mail\MailroomStats
 *
 * @property int $id
 * @property int|null $mailroom_id
 * @property int $number_outboxes
 * @property int $number_outbox_type_basket_low_stock
 * @property int $number_outbox_type_basket_reminder_1
 * @property int $number_outbox_type_basket_reminder_2
 * @property int $number_outbox_type_basket_reminder_3
 * @property int $number_outbox_type_new_customer
 * @property int $number_outbox_type_delivery_note_dispatched
 * @property int $number_outbox_type_delivery_note_undispatched
 * @property int $number_outbox_type_invoice_deleted
 * @property int $number_outbox_type_new_order
 * @property int $number_outbox_type_abandoned_cart
 * @property int $number_outbox_type_delivery_confirmation
 * @property int $number_outbox_type_reorder_reminder
 * @property int $number_outbox_type_invite
 * @property int $number_outbox_type_invite_mailshot
 * @property int $number_outbox_type_invite_full_mailshot
 * @property int $number_outbox_type_marketing
 * @property int $number_outbox_type_newsletter
 * @property int $number_outbox_type_oos_notification
 * @property int $number_outbox_type_order_confirmation
 * @property int $number_outbox_type_password_reminder
 * @property int $number_outbox_type_registration
 * @property int $number_outbox_type_registration_approved
 * @property int $number_outbox_type_registration_rejected
 * @property int $number_outbox_state_in_process
 * @property int $number_outbox_state_active
 * @property int $number_outbox_state_suspended
 * @property int $number_mailshots
 * @property int $number_mailroom_state_in_process
 * @property int $number_mailroom_state_set_recipients
 * @property int $number_mailroom_state_composing_email
 * @property int $number_mailroom_state_ready
 * @property int $number_mailroom_state_scheduled
 * @property int $number_mailroom_state_sending
 * @property int $number_mailroom_state_sent
 * @property int $number_mailroom_state_cancelled
 * @property int $number_mailroom_state_stopped
 * @property int $number_dispatched_emails
 * @property int $number_dispatched_emails_state_ready
 * @property int $number_dispatched_emails_state_sent_to_provider
 * @property int $number_dispatched_emails_state_rejected_by_provider
 * @property int $number_dispatched_emails_state_sent
 * @property int $number_dispatched_emails_state_opened
 * @property int $number_dispatched_emails_state_clicked
 * @property int $number_dispatched_emails_state_soft_bounce
 * @property int $number_dispatched_emails_state_hard_bounce
 * @property int $number_dispatched_emails_state_delivered
 * @property int $number_dispatched_emails_state_marked_as_spam
 * @property int $number_dispatched_emails_state_error
 * @property int $number_provoked_unsubscribe
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Mail\Mailroom|null $mailroom
 * @method static Builder|MailroomStats newModelQuery()
 * @method static Builder|MailroomStats newQuery()
 * @method static Builder|MailroomStats query()
 * @mixin Eloquent
 */
class MailroomStats extends Model
{
    protected $table = 'mailroom_stats';

    protected $guarded = [];

    public function mailroom(): BelongsTo
    {
        return $this->belongsTo(Mailroom::class);
    }
}
