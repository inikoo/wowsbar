<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 11:36:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\SysAdmin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationMailStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_mailshots
 * @property int $number_mailshots_type_prospect_mailshot
 * @property int $number_mailshots_type_newsletter
 * @property int $number_mailshots_type_customer_prospect_mailshot
 * @property int $number_mailshots_type_marketing
 * @property int $number_mailshots_type_announcement
 * @property int $number_mailshots_state_in_process
 * @property int $number_mailshots_state_ready
 * @property int $number_mailshots_state_scheduled
 * @property int $number_mailshots_state_sending
 * @property int $number_mailshots_state_sent
 * @property int $number_mailshots_state_cancelled
 * @property int $number_mailshots_state_stopped
 * @property int $number_mailshots_type_prospect_mailshot_state_in_process
 * @property int $number_mailshots_type_prospect_mailshot_state_ready
 * @property int $number_mailshots_type_prospect_mailshot_state_scheduled
 * @property int $number_mailshots_type_prospect_mailshot_state_sending
 * @property int $number_mailshots_type_prospect_mailshot_state_sent
 * @property int $number_mailshots_type_prospect_mailshot_state_cancelled
 * @property int $number_mailshots_type_prospect_mailshot_state_stopped
 * @property int $number_mailshots_type_newsletter_state_in_process
 * @property int $number_mailshots_type_newsletter_state_ready
 * @property int $number_mailshots_type_newsletter_state_scheduled
 * @property int $number_mailshots_type_newsletter_state_sending
 * @property int $number_mailshots_type_newsletter_state_sent
 * @property int $number_mailshots_type_newsletter_state_cancelled
 * @property int $number_mailshots_type_newsletter_state_stopped
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_in_proce
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_ready
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_schedule
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_sending
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_sent
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_cancelle
 * @property int $number_mailshots_type_customer_prospect_mailshot_state_stopped
 * @property int $number_mailshots_type_marketing_state_in_process
 * @property int $number_mailshots_type_marketing_state_ready
 * @property int $number_mailshots_type_marketing_state_scheduled
 * @property int $number_mailshots_type_marketing_state_sending
 * @property int $number_mailshots_type_marketing_state_sent
 * @property int $number_mailshots_type_marketing_state_cancelled
 * @property int $number_mailshots_type_marketing_state_stopped
 * @property int $number_mailshots_type_announcement_state_in_process
 * @property int $number_mailshots_type_announcement_state_ready
 * @property int $number_mailshots_type_announcement_state_scheduled
 * @property int $number_mailshots_type_announcement_state_sending
 * @property int $number_mailshots_type_announcement_state_sent
 * @property int $number_mailshots_type_announcement_state_cancelled
 * @property int $number_mailshots_type_announcement_state_stopped
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SysAdmin\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeAnnouncementStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateCancelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateInProce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeMarketingStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeNewsletterStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereNumberMailshotsTypeProspectMailshotStateStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationMailStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationMailStats extends Model
{
    protected $table = 'organisation_mail_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
