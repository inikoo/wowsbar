<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Market\ShopMailStats
 *
 * @property int $id
 * @property int $shop_id
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
 * @property-read \App\Models\Market\Shop $shop
 * @method static Builder|ShopMailStats newModelQuery()
 * @method static Builder|ShopMailStats newQuery()
 * @method static Builder|ShopMailStats query()
 * @method static Builder|ShopMailStats whereCreatedAt($value)
 * @method static Builder|ShopMailStats whereId($value)
 * @method static Builder|ShopMailStats whereNumberMailshots($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateCancelled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateInProcess($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateScheduled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsStateStopped($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncement($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateCancelled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateInProcess($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateScheduled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeAnnouncementStateStopped($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshot($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateCancelle($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateInProce($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSchedule($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeCustomerProspectMailshotStateStopped($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketing($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateCancelled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateInProcess($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateScheduled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeMarketingStateStopped($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletter($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateCancelled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateInProcess($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateScheduled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeNewsletterStateStopped($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshot($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateCancelled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateInProcess($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateReady($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateScheduled($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateSending($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateSent($value)
 * @method static Builder|ShopMailStats whereNumberMailshotsTypeProspectMailshotStateStopped($value)
 * @method static Builder|ShopMailStats whereShopId($value)
 * @method static Builder|ShopMailStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ShopMailStats extends Model
{
    protected $table = 'shop_mail_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
