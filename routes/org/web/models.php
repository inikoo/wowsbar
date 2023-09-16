<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 13:32:22 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Accounting\Payment\UpdatePayment;
use App\Actions\Organisation\Accounting\PaymentAccount\StorePaymentAccount;
use App\Actions\Organisation\Accounting\PaymentAccount\UpdatePaymentAccount;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\DeletePaymentServiceProvider;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UpdatePaymentServiceProvider;
use App\Actions\Organisation\HumanResources\Employee\DeleteEmployee;
use App\Actions\Organisation\HumanResources\Employee\StoreEmployee;
use App\Actions\Organisation\HumanResources\Employee\UpdateEmployee;
use App\Actions\Organisation\Market\Shop\StoreShop;
use App\Actions\Organisation\Organisation\UpdateOrganisation;
use App\Actions\Organisation\Web\Webpage\StoreArticle;
use App\Actions\Organisation\Web\Webpage\StoreWebpage;
use App\Actions\Organisation\Web\Website\StoreWebsite;
use App\Actions\Organisation\Web\Website\UpdateWebsite;
use App\Actions\Organisation\Web\Website\UpdateWebsiteState;
use App\Actions\UI\Organisation\Profile\UpdateProfile;

Route::patch('/profile', UpdateProfile::class)->name('profile.update');
Route::post('/shop/', StoreShop::class)->name('shop.store');

Route::patch('/employee/{employee}', UpdateEmployee::class)->name('employee.update');
Route::post('/employee/', StoreEmployee::class)->name('employee.store');
Route::delete('/employee/{employee}', DeleteEmployee::class)->name('employee.delete');
Route::post('/website/', StoreWebsite::class)->name('website.store');
Route::patch('/organisation', UpdateOrganisation::class)->name('organisation.update');
Route::patch('/website', UpdateWebsite::class)->name('website.update');
Route::patch('/website/state', UpdateWebsiteState::class)->name('website.state.update');
Route::post('/webpage/{webpage:id}', StoreWebpage::class)->name('webpage.store');
Route::post('/article/{webpage:id}', StoreArticle::class)->name('article.store');


Route::patch('/provider/{paymentServiceProvider}', UpdatePaymentServiceProvider::class)->name('payment-service-provider.update');
Route::delete('/provider/{paymentServiceProvider}', DeletePaymentServiceProvider::class)->name('payment-service-provider.delete');

Route::patch('/payment/{payment}', UpdatePayment::class)->name('payment.update');

Route::patch('/payment-account/{paymentAccount}', UpdatePaymentAccount::class)->name('payment-account.update');
Route::post('/payment-account', StorePaymentAccount::class)->name('payment-account.store');
