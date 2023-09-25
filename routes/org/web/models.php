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
use App\Actions\CRM\Appointment\AssignAppointmentUser;
use App\Actions\CRM\Appointment\StoreAppointment;
use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\CRM\Customer\UpdateCustomer;
use App\Actions\Portfolios\CustomerWebsite\StoreCustomerWebsite;
use App\Actions\HumanResources\Employee\DeleteEmployee;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\HumanResources\Employee\UploadEmployee;
use App\Actions\HumanResources\Workplace\DeleteWorkplace;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\HumanResources\Workplace\UpdateWorkplace;
use App\Actions\Leads\Prospect\RemoveProspect;
use App\Actions\Leads\Prospect\UploadShopProspects;
use App\Actions\Market\Product\StoreProduct;
use App\Actions\Market\Product\UpdateProduct;
use App\Actions\Market\Product\UploadProduct;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Organisation\Guest\UploadGuest;
use App\Actions\Organisation\Organisation\UpdateOrganisation;
use App\Actions\Portfolio\PortfolioWebsite\UploadPortfolioWebsite;
use App\Actions\UI\Organisation\Profile\UpdateProfile;
use App\Actions\Web\Webpage\StoreArticle;
use App\Actions\Web\Webpage\StoreWebpage;
use App\Actions\Web\Website\StoreWebsite;
use App\Actions\Web\Website\UpdateWebsite;
use App\Actions\Web\Website\UpdateWebsiteFooter;
use App\Actions\Web\Website\UpdateWebsiteHeader;
use App\Actions\Web\Website\UpdateWebsiteLayout;
use App\Actions\Web\Website\UpdateWebsiteState;
use App\Actions\Web\Website\UploadImagesToWebsite;

Route::patch('/profile', UpdateProfile::class)->name('profile.update');
Route::post('/shop/', StoreShop::class)->name('shop.store');
Route::patch('/employee/{employee}', UpdateEmployee::class)->name('employee.update');
Route::post('/employee/', StoreEmployee::class)->name('employee.store');
Route::delete('/employee/{employee}', DeleteEmployee::class)->name('employee.delete');
Route::post('/shop/{shop:id}/website/', StoreWebsite::class)->name('shop.website.store');
Route::post('/shop/{shop:id}/customer/', [StoreCustomer::class,'inShop'])->name('shop.customer.store');

Route::patch('/organisation', UpdateOrganisation::class)->name('organisation.update');

Route::post('/article/{webpage:id}', StoreArticle::class)->name('article.store');
Route::post('/employees/imports/upload', UploadEmployee::class)->name('employees.upload');
Route::post('/shop/{shop:id}/prospect/upload', UploadShopProspects::class)->name('shop.prospect.upload');
Route::delete('/prospect/{prospect:id}', RemoveProspect::class)->name('prospect.remove');

Route::post('/products/imports/upload', UploadProduct::class)->name('products.upload');
Route::post('/guests/imports/upload', UploadGuest::class)->name('guests.upload');
Route::patch('/provider/{paymentServiceProvider}', UpdatePaymentServiceProvider::class)->name('payment-service-provider.update');
Route::delete('/provider/{paymentServiceProvider}', DeletePaymentServiceProvider::class)->name('payment-service-provider.delete');
Route::patch('/payment/{payment}', UpdatePayment::class)->name('payment.update');
Route::patch('/payment-account/{paymentAccount}', UpdatePaymentAccount::class)->name('payment-account.update');
Route::post('/payment-account', StorePaymentAccount::class)->name('payment-account.store');
Route::post('/shop/{shop}/product', [StoreProduct::class, 'inShop'])->name('show.product.store');
Route::patch('/product/{product}', UpdateProduct::class)->name('product.update');
Route::delete('/product/{product}', UpdateProduct::class)->name('product.delete');

Route::prefix('website')->as('website.')->group(function () {
    Route::patch('{website:id}', UpdateWebsite::class)->name('update');
    Route::patch('{website:id}/state', UpdateWebsiteState::class)->name('state.update');
    Route::post('{website:id}/images', UploadImagesToWebsite::class)->name('images.store');
    Route::patch('{website:id}/header', UpdateWebsiteHeader::class)->name('header.update');
    Route::patch('{website:id}/footer', UpdateWebsiteFooter::class)->name('footer.update');
    Route::patch('{website:id}/layout', UpdateWebsiteLayout::class)->name('layout.update');

});

Route::prefix('webpage')->as('webpage.')->group(function () {
    Route::post('{webpage:id}', StoreWebpage::class)->name('store');
    //Route::patch('{webpage:id}', UpdateWebsite::class)->name('update');
    //Route::patch('{webpage:id}/state', UpdateWebsiteState::class)->name('state.update');
    //Route::post('{webpage:id}/images', UploadImagesToWebsite::class)->name('images.store');
});

Route::prefix('appointment')->as('appointment.')->group(function () {
    Route::post('/', StoreAppointment::class)->name('store');
    Route::patch('/assign/{organisationUser}', AssignAppointmentUser::class)->name('assign');
});

Route::patch('/workplace/{workplace}', UpdateWorkplace::class)->name('workplace.update');
Route::post('/workplace/', StoreWorkplace::class)->name('workplace.store');
Route::delete('/workplace/{workplace}', DeleteWorkplace::class)->name('workplace.delete');

Route::patch('/customer/{customer:id}', UpdateCustomer::class)->name('customer.update');
Route::post('/customer/{customer:id}/websites/upload', UploadPortfolioWebsite::class)->name('customer.website.upload');
Route::post('/customer/{customer:id}/websites', StoreCustomerWebsite::class)->name('customer.customer-website.store');
