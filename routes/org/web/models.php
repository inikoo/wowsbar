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
use App\Actions\Catalogue\ProductCategory\UpdateProductCategory;
use App\Actions\CRM\Appointment\AssignAppointmentUser;
use App\Actions\CRM\Appointment\StoreAppointment;
use App\Actions\CRM\Appointment\UpdateAppointment;
use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\CRM\Customer\UpdateCustomer;
use App\Actions\HumanResources\Employee\DeleteEmployee;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\HumanResources\Employee\ImportEmployees;
use App\Actions\HumanResources\Workplace\DeleteWorkplace;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\HumanResources\Workplace\UpdateWorkplace;
use App\Actions\Leads\Prospect\RemoveProspect;
use App\Actions\Leads\Prospect\ImportShopProspects;
use App\Actions\Leads\Prospect\StoreProspect;
use App\Actions\Leads\Prospect\UpdateProspect;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Organisation\Guest\DeleteGuest;
use App\Actions\Organisation\Guest\ImportGuest;
use App\Actions\Organisation\Guest\StoreGuest;
use App\Actions\Organisation\Guest\UpdateGuest;
use App\Actions\Organisation\Organisation\UpdateOrganisation;
use App\Actions\Portfolio\PortfolioDivision\SyncDivisionPortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\ImportPortfolioWebsite;
use App\Actions\Portfolios\CustomerWebsite\StoreCustomerWebsite;
use App\Actions\Catalogue\Product\StoreProduct;
use App\Actions\Catalogue\Product\UpdateProduct;
use App\Actions\Catalogue\Product\ImportProducts;
use App\Actions\Portfolios\CustomerWebsite\UpdateCustomerWebsite;
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
Route::patch('/employee/{employee}', UpdateEmployee::class)->name('employee.update');
Route::post('/employee/', StoreEmployee::class)->name('employee.store');
Route::delete('/employee/{employee}', DeleteEmployee::class)->name('employee.delete');


Route::patch('/organisation', UpdateOrganisation::class)->name('organisation.update');

Route::post('/article/{webpage:id}', StoreArticle::class)->name('article.store');
Route::post('/employees/imports/upload', ImportEmployees::class)->name('employees.upload');
Route::delete('/prospect/{prospect:id}', RemoveProspect::class)->name('prospect.remove');

Route::post('/products/imports/upload', ImportProducts::class)->name('products.upload');

Route::post('/guests/imports/upload', ImportGuest::class)->name('guests.upload');
Route::patch('/guest/{guest}', UpdateGuest::class)->name('guests.update');
Route::post('/guest', StoreGuest::class)->name('guests.store');
Route::delete('/guest/{guest}', DeleteGuest::class)->name('guests.delete');

Route::patch('/provider/{paymentServiceProvider}', UpdatePaymentServiceProvider::class)->name('payment-service-provider.update');
Route::delete('/provider/{paymentServiceProvider}', DeletePaymentServiceProvider::class)->name('payment-service-provider.delete');
Route::patch('/payment/{payment}', UpdatePayment::class)->name('payment.update');
Route::patch('/payment-account/{paymentAccount}', UpdatePaymentAccount::class)->name('payment-account.update');
Route::post('/payment-account', StorePaymentAccount::class)->name('payment-account.store');
Route::patch('/product/{product}', UpdateProduct::class)->name('product.update');
Route::delete('/product/{product}', UpdateProduct::class)->name('product.delete');

Route::patch('product-category/{productCategory}', UpdateProductCategory::class)->name('product-category.update');

Route::prefix('shop')->as('shop.')->group(function () {
    Route::post('', StoreShop::class)->name('store');
    Route::prefix('{shop:id}')->group(function () {
        Route::post('website/', StoreWebsite::class)->name('website.store');
        Route::post('customer/', [StoreCustomer::class, 'inShop'])->name('customer.store');
        Route::post('prospect/upload', ImportShopProspects::class)->name('prospect.upload');
        Route::post('prospect', [StoreProspect::class, 'inShop'])->name('prospect.store');
        Route::patch('prospect/{prospect:id}', [UpdateProspect::class, 'inShop'])->name('prospect.update');
        Route::post('product', [StoreProduct::class, 'inShop'])->name('product.store');

        Route::post('/', [StoreAppointment::class, 'inShop'])->name('appointment.store');
    });
});

Route::prefix('website')->as('website.')->group(function () {
    Route::patch('{website:id}', UpdateWebsite::class)->name('update');
    Route::patch('{website:id}/state', UpdateWebsiteState::class)->name('state.update');
    Route::patch('{website:id}/layout', UpdateWebsiteLayout::class)->name('layout.update');

    Route::post('{website:id}/images/header', [UploadImagesToWebsite::class,'header'])->name('header.images.store');
    Route::post('{website:id}/images/footer', [UploadImagesToWebsite::class,'footer'])->name('footer.images.store');
    Route::post('{website:id}/images/favicon', [UploadImagesToWebsite::class,'favicon'])->name('favicon.images.store');

    Route::post('{website:id}/header/content', UpdateWebsiteHeaderContent::class)->name('header.content.update');
    Route::post('{website:id}/header/publish', [PublishWebsiteMarginal::class,'header'])->name('header.content.publish');
    Route::get('{website:id}/header/content', ShowWebsiteHeaderContent::class)->name('header.content.show');

    Route::post('{website:id}/footer/content', UpdateWebsiteFooterContent::class)->name('footer.content.update');
    Route::post('{website:id}/footer/publish', [PublishWebsiteMarginal::class,'footer'])->name('footer.content.publish');
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
    Route::patch('/{appointment}', UpdateAppointment::class)->name('update');
    Route::patch('/assign/{organisationUser}', AssignAppointmentUser::class)->name('assign');
});

Route::patch('/workplace/{workplace}', UpdateWorkplace::class)->name('workplace.update');
Route::post('/workplace/', StoreWorkplace::class)->name('workplace.store');
Route::delete('/workplace/{workplace}', DeleteWorkplace::class)->name('workplace.delete');

Route::prefix('customer/{customer:id}')->as('customer.')->group(function () {
    Route::patch('', UpdateCustomer::class)->name('update');
    Route::post('websites/upload', ImportPortfolioWebsite::class)->name('website.upload');
    Route::post('websites', StoreCustomerWebsite::class)->name('customer-website.store');
});

Route::patch('websites/{customerWebsite}', UpdateCustomerWebsite::class)->name('customer-website.update');

Route::post('{portfolioWebsite}/interest', SyncDivisionPortfolioWebsite::class)->name('interest.store');
