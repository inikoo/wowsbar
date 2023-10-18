<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_guests
 * @property int $number_guests_status_active
 * @property int $number_guests_status_inactive
 * @property int $number_organisation_users
 * @property int $number_organisation_users_status_active
 * @property int $number_organisation_users_status_inactive
 * @property int $number_organisation_users_type_employee
 * @property int $number_organisation_users_type_guest
 * @property int $number_images
 * @property int $filesize_images
 * @property int $number_attachments
 * @property int $filesize_attachments
 * @property int $number_shops
 * @property int $number_shops_type_digital_marketing
 * @property int $number_shops_type_content_as_a_service
 * @property int $number_shops_state_in_process
 * @property int $number_shops_state_open
 * @property int $number_shops_state_closing_down
 * @property int $number_shops_state_closed
 * @property int $number_websites
 * @property int $number_websites_state_in_process
 * @property int $number_websites_state_live
 * @property int $number_websites_state_closed
 * @property int $number_webpages
 * @property int $number_webpages_type_storefront
 * @property int $number_webpages_type_shop
 * @property int $number_webpages_type_content
 * @property int $number_webpages_type_small_print
 * @property int $number_webpages_type_engagement
 * @property int $number_webpages_type_auth
 * @property int $number_webpages_type_blog
 * @property int $number_webpages_purpose_storefront
 * @property int $number_webpages_purpose_product
 * @property int $number_webpages_purpose_info
 * @property int $number_webpages_purpose_privacy
 * @property int $number_webpages_purpose_cookies_policy
 * @property int $number_webpages_purpose_terms_and_conditions
 * @property int $number_webpages_purpose_appointment
 * @property int $number_webpages_purpose_contact
 * @property int $number_webpages_purpose_login
 * @property int $number_webpages_purpose_register
 * @property int $number_webpages_purpose_blog
 * @property int $number_webpages_purpose_article
 * @property int $number_webpages_purpose_content
 * @property int $number_webpages_purpose_other_small_print
 * @property int $number_webpages_purpose_shop
 * @property int $number_webpages_state_in_process
 * @property int $number_webpages_state_ready
 * @property int $number_webpages_state_live
 * @property int $number_webpages_state_closed
 * @property int $number_uploaded_images
 * @property int $number_uploaded_images_scope_landscape
 * @property int $number_uploaded_images_scope_square
 * @property int $number_logins
 * @property string|null $last_login_at
 * @property string|null $last_active_at
 * @property int $number_failed_logins
 * @property string|null $last_failed_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static Builder|OrganisationStats newModelQuery()
 * @method static Builder|OrganisationStats newQuery()
 * @method static Builder|OrganisationStats query()
 * @method static Builder|OrganisationStats whereCreatedAt($value)
 * @method static Builder|OrganisationStats whereFilesizeAttachments($value)
 * @method static Builder|OrganisationStats whereFilesizeImages($value)
 * @method static Builder|OrganisationStats whereId($value)
 * @method static Builder|OrganisationStats whereLastActiveAt($value)
 * @method static Builder|OrganisationStats whereLastFailedLoginAt($value)
 * @method static Builder|OrganisationStats whereLastLoginAt($value)
 * @method static Builder|OrganisationStats whereNumberAttachments($value)
 * @method static Builder|OrganisationStats whereNumberFailedLogins($value)
 * @method static Builder|OrganisationStats whereNumberGuests($value)
 * @method static Builder|OrganisationStats whereNumberGuestsStatusActive($value)
 * @method static Builder|OrganisationStats whereNumberGuestsStatusInactive($value)
 * @method static Builder|OrganisationStats whereNumberImages($value)
 * @method static Builder|OrganisationStats whereNumberLogins($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsers($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersStatusActive($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersStatusInactive($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersTypeEmployee($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersTypeGuest($value)
 * @method static Builder|OrganisationStats whereNumberShops($value)
 * @method static Builder|OrganisationStats whereNumberShopsStateClosed($value)
 * @method static Builder|OrganisationStats whereNumberShopsStateClosingDown($value)
 * @method static Builder|OrganisationStats whereNumberShopsStateInProcess($value)
 * @method static Builder|OrganisationStats whereNumberShopsStateOpen($value)
 * @method static Builder|OrganisationStats whereNumberShopsTypeContentAsAService($value)
 * @method static Builder|OrganisationStats whereNumberShopsTypeDigitalMarketing($value)
 * @method static Builder|OrganisationStats whereNumberUploadedImages($value)
 * @method static Builder|OrganisationStats whereNumberUploadedImagesScopeLandscape($value)
 * @method static Builder|OrganisationStats whereNumberUploadedImagesScopeSquare($value)
 * @method static Builder|OrganisationStats whereNumberWebpages($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeAppointment($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeArticle($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeBlog($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeContact($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeContent($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeCookiesPolicy($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeInfo($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeLogin($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeOtherSmallPrint($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposePrivacy($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeProduct($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeRegister($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeShop($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeStorefront($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesPurposeTermsAndConditions($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesStateClosed($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesStateInProcess($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesStateLive($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesStateReady($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeAuth($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeBlog($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeContent($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeEngagement($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeShop($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeSmallPrint($value)
 * @method static Builder|OrganisationStats whereNumberWebpagesTypeStorefront($value)
 * @method static Builder|OrganisationStats whereNumberWebsites($value)
 * @method static Builder|OrganisationStats whereNumberWebsitesStateClosed($value)
 * @method static Builder|OrganisationStats whereNumberWebsitesStateInProcess($value)
 * @method static Builder|OrganisationStats whereNumberWebsitesStateLive($value)
 * @method static Builder|OrganisationStats whereOrganisationId($value)
 * @method static Builder|OrganisationStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationStats extends Model
{
    protected $table = 'organisation_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
