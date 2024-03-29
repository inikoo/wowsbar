<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 13:32:22 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Accounting\Payment\UpdatePayment;
use App\Actions\Accounting\PaymentAccount\StorePaymentAccount;
use App\Actions\Accounting\PaymentAccount\UpdatePaymentAccount;
use App\Actions\Accounting\PaymentServiceProvider\DeletePaymentServiceProvider;
use App\Actions\Accounting\PaymentServiceProvider\UpdatePaymentServiceProvider;
use App\Actions\Catalogue\Product\ImportProducts;
use App\Actions\Catalogue\Product\StoreProduct;
use App\Actions\Catalogue\Product\UpdateProduct;
use App\Actions\Catalogue\ProductCategory\UpdateProductCategory;
use App\Actions\CRM\Appointment\AssignAppointmentUser;
use App\Actions\CRM\Appointment\StoreAppointment;
use App\Actions\CRM\Appointment\UpdateAppointment;
use App\Actions\CRM\Customer\DeleteCustomer;
use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\CRM\Customer\Surveys\StoreSurvey;
use App\Actions\CRM\Customer\UpdateCustomer;
use App\Actions\CRM\CustomerWebsite\StoreCustomerWebsite;
use App\Actions\CRM\CustomerWebsite\UpdateCustomerWebsite;
use App\Actions\CRM\Shipping\ShipperAccount\StoreShipperAccount;
use App\Actions\CRM\User\StoreOrgCustomerUser;
use App\Actions\CRM\User\UpdateOrgCustomerUser;
use App\Actions\Helpers\AwsEmail\SendIdentityEmailVerification;
use App\Actions\Helpers\LiveOrganisationUsersCurrentPage\DeleteLiveUsers;
use App\Actions\Helpers\LiveOrganisationUsersCurrentPage\IndexLiveOrganisationUsersCurrentPage;
use App\Actions\Helpers\LiveOrganisationUsersCurrentPage\StoreOrganisationLiveUsersCurrentPage;
use App\Actions\Helpers\Tag\StoreTag;
use App\Actions\Helpers\Tag\UpdateTag;
use App\Actions\HumanResources\Employee\DeleteEmployee;
use App\Actions\HumanResources\Employee\ImportEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\HumanResources\Workplace\DeleteWorkplace;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\HumanResources\Workplace\UpdateWorkplace;
use App\Actions\Leads\Prospect\ImportShopProspects;
use App\Actions\Leads\Prospect\Queries\StoreProspectQuery;
use App\Actions\Leads\Prospect\Queries\UpdateProspectQuery;
use App\Actions\Leads\Prospect\RemoveProspect;
use App\Actions\Leads\Prospect\StoreProspect;
use App\Actions\Leads\Prospect\Tags\DeleteTagsProspect;
use App\Actions\Leads\Prospect\Tags\SyncTagsProspect;
use App\Actions\Leads\Prospect\UpdateProspect;
use App\Actions\Leads\Prospect\UpdateProspectEmailUndoUnsubscribed;
use App\Actions\Leads\Prospect\UpdateProspectEmailUnsubscribed;
use App\Actions\Mail\EmailTemplate\StoreEmailTemplate;
use App\Actions\Mail\EmailTemplate\UI\ShowEmailTemplateContent;
use App\Actions\Mail\EmailTemplate\UpdateEmailTemplateContent;
use App\Actions\Mail\Mailshot\DeleteMailshot;
use App\Actions\Mail\Mailshot\ResumeMailshot;
use App\Actions\Mail\Mailshot\SendMailshot;
use App\Actions\Mail\Mailshot\SendMailshotTest;
use App\Actions\Mail\Mailshot\SetMailshotAsReady;
use App\Actions\Mail\Mailshot\SetMailshotAsScheduled;
use App\Actions\Mail\Mailshot\ShowMailshotContent;
use App\Actions\Mail\Mailshot\StopMailshot;
use App\Actions\Mail\Mailshot\StopMailshotScheduled;
use App\Actions\Mail\Mailshot\StoreMailshot;
use App\Actions\Mail\Mailshot\UpdateMailshot;
use App\Actions\Mail\Mailshot\UpdateMailshotContent;
use App\Actions\Mail\Mailshot\UpdateProspectsMailshotSetting;
use App\Actions\Mail\Mailshot\UploadImagesToMailshot;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Market\Shop\UpdateShop;
use App\Actions\Portfolio\PortfolioDivision\SyncDivisionPortfolioWebsite;
use App\Actions\SysAdmin\Guest\DeleteGuest;
use App\Actions\SysAdmin\Guest\ImportGuests;
use App\Actions\SysAdmin\Guest\StoreGuest;
use App\Actions\SysAdmin\Guest\UpdateGuest;
use App\Actions\SysAdmin\Organisation\UpdateOrganisation;
use App\Actions\SysAdmin\OrganisationUser\UpdateOrganisationUser;
use App\Actions\UI\Organisation\Profile\GetProfileAppLoginQRCode;
use App\Actions\UI\Organisation\Profile\UpdateProfile;
use App\Actions\Web\Webpage\PublishWebpage;
use App\Actions\Web\Webpage\ShowWebpageContent;
use App\Actions\Web\Webpage\StoreArticle;
use App\Actions\Web\Webpage\StoreWebpage;
use App\Actions\Web\Webpage\UpdateWebpageContent;
use App\Actions\Web\Webpage\UploadImagesToWebpage;
use App\Actions\Web\Website\PublishWebsiteMarginal;
use App\Actions\Web\Website\ShowWebsiteFooterContent;
use App\Actions\Web\Website\ShowWebsiteHeaderContent;
use App\Actions\Web\Website\StoreWebsite;
use App\Actions\Web\Website\UpdateWebsite;
use App\Actions\Web\Website\UpdateWebsiteFooterContent;
use App\Actions\Web\Website\UpdateWebsiteHeaderContent;
use App\Actions\Web\Website\UpdateWebsiteLayout;
use App\Actions\Web\Website\UpdateWebsiteState;
use App\Actions\Web\Website\UploadImagesToWebsite;

Route::patch('/profile', UpdateProfile::class)->name('profile.update');
Route::get('/profile/app-login-qrcode', GetProfileAppLoginQRCode::class)->name('profile.app-login-qrcode');

Route::patch('/employee/{employee:id}', UpdateEmployee::class)->name('employee.update');
Route::post('/employee/', StoreEmployee::class)->name('employee.store');
Route::delete('/employee/{employee:id}', DeleteEmployee::class)->name('employee.delete');

Route::post('live-organisation-users-current-page/{organisationUser:id}', StoreOrganisationLiveUsersCurrentPage::class)->name('live-organisation-users-current-page.store');
Route::delete('live-users', DeleteLiveUsers::class)->name('live-users.delete');
Route::get('live-organisation-users-current-page', IndexLiveOrganisationUsersCurrentPage::class)->name('live-organisation-users-current-page.index');


Route::patch('/organisation', UpdateOrganisation::class)->name('organisation.update');

Route::post('/article/{webpage:id}', StoreArticle::class)->name('article.store');
Route::post('/employees/imports/upload', ImportEmployees::class)->name('employees.upload');
Route::delete('/prospect/{prospect}', RemoveProspect::class)->name('prospect.remove');

Route::post('/prospect/{prospect:id}/tags', SyncTagsProspect::class)->name('prospect.tag.attach');

Route::post('/shop/{shop:id}/prospect/tags', [StoreTag::class, 'inShop'])->name('shop.prospect.tag.store');
Route::patch('/shop/{shop}/prospect/tags/{tag}', [UpdateTag::class, 'inShop'])->name('shop.prospect.tag.update');

Route::post('/prospect/tags', [StoreTag::class, 'inProspect'])->name('prospect.tag.store');
Route::patch('/prospect/tags/{tag}', [UpdateTag::class, 'inProspect'])->name('prospect.tag.update');
Route::get('/shop/{shop}/prospect/tags/{tag}', DeleteTagsProspect::class)->name('prospect.tag.delete');

Route::post('/products/imports/upload', ImportProducts::class)->name('products.upload');

Route::post('/guests/imports/upload', ImportGuests::class)->name('guests.upload');
Route::patch('/guest/{guest:id}', UpdateGuest::class)->name('guests.update');
Route::post('/guest', StoreGuest::class)->name('guests.store');
Route::delete('/guest/{guest:id}', DeleteGuest::class)->name('guests.delete');

Route::patch('/provider/{paymentServiceProvider:id}', UpdatePaymentServiceProvider::class)->name('payment-service-provider.update');
Route::delete('/provider/{paymentServiceProvider:id}', DeletePaymentServiceProvider::class)->name('payment-service-provider.delete');
Route::patch('/payment/{payment:id}', UpdatePayment::class)->name('payment.update');
Route::patch('/payment-account/{paymentAccount:id}', UpdatePaymentAccount::class)->name('payment-account.update');
Route::post('/payment-account', StorePaymentAccount::class)->name('payment-account.store');
Route::patch('/product/{product:id}', UpdateProduct::class)->name('product.update');
Route::delete('/product/{product:id}', UpdateProduct::class)->name('product.delete');

Route::patch('product-category/{productCategory}', UpdateProductCategory::class)->name('product-category.update');
Route::post('prospect/mailshots/{mailshot:id}/email_template', [StoreEmailTemplate::class, 'fromMailshot'])->name('prospect-mailshot.email_templates.store');


Route::prefix('shop')->as('shop.')->group(function () {
    Route::post('', StoreShop::class)->name('store');
    Route::patch('', UpdateShop::class)->name('update');
    Route::prefix('{shop:id}')->group(function () {
        Route::post('website/', StoreWebsite::class)->name('website.store');
        Route::post('customer/', [StoreCustomer::class, 'inShop'])->name('customer.store');
        Route::post('prospect/upload', [ImportShopProspects::class, 'inShop'])->name('prospects.upload');
        Route::post('prospect', [StoreProspect::class, 'inShop'])->name('prospect.store');
        Route::patch('prospect/{prospect:id}', [UpdateProspect::class, 'inShop'])->name('prospect.update');
        Route::patch('prospect/{prospect:id}/unsubscribe', [UpdateProspectEmailUnsubscribed::class, 'inShop'])->name('prospect.unsubscribe.update');
        Route::patch('prospect/{prospect:id}/undo_unsubscribe', [UpdateProspectEmailUndoUnsubscribed::class, 'inShop'])->name('prospect.undo_unsubscribe.update');

        Route::post('product', [StoreProduct::class, 'inShop'])->name('product.store');
        Route::patch('prospect/mailshots/settings', UpdateProspectsMailshotSetting::class)->name('prospect-mailshots.settings.update');
        Route::post('prospect/mailshots/settings/email/resend', [SendIdentityEmailVerification::class, 'inShop'])->name('prospect-mailshots.settings.email-verification.resend');
        Route::patch('prospect/mailshots/{mailshot:id}', [UpdateMailshot::class, 'shopProspects'])->name('prospect-mailshot.update');

        Route::post('prospect/mailshots', [StoreMailshot::class, 'shopProspects'])->name('prospect-mailshot.store');
        Route::post('prospect-queries', [StoreProspectQuery::class, 'inShop'])->name('prospect-query.store');
        Route::patch('prospect-queries/{query}', [UpdateProspectQuery::class, 'inShop'])->name('prospect-query.update');
        Route::post('/', [StoreAppointment::class, 'inShop'])->name('appointment.store');
        Route::post('surveys', [StoreSurvey::class, 'inShop'])->name('surveys.store');

        Route::post('customer/{customer:id}/shipper-accounts', [StoreShipperAccount::class, 'inShopInCustomer'])->name('customer.shipper-account.store');
    });
});

Route::prefix('website')->as('website.')->group(function () {
    Route::patch('{website:id}', UpdateWebsite::class)->name('update');
    Route::patch('{website:id}/state', UpdateWebsiteState::class)->name('state.update');
    Route::patch('{website:id}/layout', UpdateWebsiteLayout::class)->name('layout.update');

    Route::post('{website:id}/images/header', [UploadImagesToWebsite::class, 'header'])->name('header.images.store');
    Route::post('{website:id}/images/footer', [UploadImagesToWebsite::class, 'footer'])->name('footer.images.store');
    Route::post('{website:id}/images/favicon', [UploadImagesToWebsite::class, 'favicon'])->name('favicon.images.store');

    Route::post('{website:id}/header/content', UpdateWebsiteHeaderContent::class)->name('header.content.update');
    Route::post('{website:id}/header/publish', [PublishWebsiteMarginal::class, 'header'])->name('header.content.publish');
    Route::get('{website:id}/header/content', ShowWebsiteHeaderContent::class)->name('header.content.show');

    Route::post('{website:id}/footer/content', UpdateWebsiteFooterContent::class)->name('footer.content.update');
    Route::post('{website:id}/footer/publish', [PublishWebsiteMarginal::class, 'footer'])->name('footer.content.publish');
    Route::get('{website:id}/footer/content', ShowWebsiteFooterContent::class)->name('footer.content.show');
});

Route::prefix('webpage')->as('webpage.')->group(function () {
    Route::post('{webpage:id}', StoreWebpage::class)->name('store');
    Route::post('{webpage:id}/content', UpdateWebpageContent::class)->name('content.update');
    Route::post('{webpage:id}/publish', PublishWebpage::class)->name('content.publish');
    Route::get('{webpage:id}/content', ShowWebpageContent::class)->name('content.show');

    //Route::patch('{webpage:id}', UpdateWebsite::class)->name('update');
    //Route::patch('{webpage:id}/state', UpdateWebsiteState::class)->name('state.update');
    Route::post('{webpage:id}/images', UploadImagesToWebpage::class)->name('images.store');
});

Route::prefix('appointment')->as('appointment.')->group(function () {
    Route::post('/', StoreAppointment::class)->name('store');
    Route::patch('/{appointment:id}', UpdateAppointment::class)->name('update');
    Route::patch('/assign/{organisationUser:id}', AssignAppointmentUser::class)->name('assign');
});

Route::patch('/workplace/{workplace:id}', UpdateWorkplace::class)->name('workplace.update');
Route::post('/workplace/', StoreWorkplace::class)->name('workplace.store');
Route::delete('/workplace/{workplace:id}', DeleteWorkplace::class)->name('workplace.delete');

Route::prefix('customer/{customer:id}')->as('customer.')->group(function () {
    Route::patch('', UpdateCustomer::class)->name('update');
    Route::post('websites', StoreCustomerWebsite::class)->name('customer-website.store');
    Route::post('users', StoreOrgCustomerUser::class)->name('customer-user.store');
    Route::delete('', DeleteCustomer::class)->name('delete');
});

Route::patch('websites/{customerWebsite:id}', UpdateCustomerWebsite::class)->name('customer-website.update');

Route::patch('{portfolioWebsite}/interest', SyncDivisionPortfolioWebsite::class)->name('interest.store');

Route::patch('/organisation-user/{organisationUser:id}', UpdateOrganisationUser::class)->name('organisation-user.update');
Route::patch('/customer-user/{customerUser:id}', UpdateOrgCustomerUser::class)->name('customer-user.update');

Route::prefix('mailshot')->as('mailshot.')->group(function () {
    Route::post('{mailshot:id}/content', UpdateMailshotContent::class)->name('content.update');

    Route::post('{mailshot:id}/send', SendMailshot::class)->name('send');
    Route::post('{mailshot:id}/stop', StopMailshot::class)->name('stop');
    Route::post('{mailshot:id}/resume', ResumeMailshot::class)->name('resume');

    Route::delete('{mailshot:id}/delete', DeleteMailshot::class)->name('delete');

    Route::post('{mailshot:id}/send/test', SendMailshotTest::class)->name('send.test');

    Route::post('{mailshot:id}/ready', SetMailshotAsReady::class)->name('state.ready');
    Route::post('{mailshot:id}/scheduled/stop', StopMailshotScheduled::class)->name('state.scheduled.stop');
    Route::post('{mailshot:id}/scheduled', SetMailshotAsScheduled::class)->name('state.scheduled');

    Route::get('{mailshot:id}/content', ShowMailshotContent::class)->name('content.show');
    Route::post('{mailshot:id}/images', UploadImagesToMailshot::class)->name('images.store');

});

Route::prefix('email-templates')->as('email-templates.')->group(function () {
    Route::post('{emailTemplate:id}/publish', UpdateMailshotContent::class)->name('content.publish');
    Route::post('{emailTemplate:id}/content', UpdateEmailTemplateContent::class)->name('content.update');
    Route::post('{emailTemplate:id}/images', UploadImagesToMailshot::class)->name('images.store');
    Route::get('{emailTemplate:id}/content', ShowEmailTemplateContent::class)->name('content.show');
});
