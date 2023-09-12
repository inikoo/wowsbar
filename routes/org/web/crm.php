<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Prospect\IndexProspects;
use App\Actions\Organisation\CRM\Customer\UI\EditCustomer;
use App\Actions\Organisation\CRM\Customer\UI\IndexCustomers;
use App\Actions\Organisation\CRM\Customer\UI\ShowCustomer;
use App\Actions\Organisation\UI\CRM\CRMDashboard;

Route::get('/', [CRMDashboard::class, 'inTenant'])->name('dashboard');

Route::prefix('customers')->as('customers.')->group(function () {
    Route::get('/', IndexCustomers::class)->name('index');
    Route::get('/{customer}', [ShowCustomer::class, 'inTenant'])->name('show');
    Route::get('/{customer}/edit', [EditCustomer::class, 'inTenant'])->name('edit');
    //Route::get('/customers/{customer}/delete', RemoveCustomer::class)->name('customers.remove');
    //Route::get('/customers/{customer}/web-users', [IndexWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.index');
    //Route::get('/customers/{customer}/web-users/{webUser}', [ShowWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.show');
    //Route::get('/customers/{customer}/web-users/{webUser}/edit', [EditWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.edit');
});

Route::prefix('prospects')->as('prospects.')->group(function () {
    Route::get('/', [IndexProspects::class, 'inTenant'])->name('index');
    //Route::get('/customers/{customer}/delete', RemoveCustomer::class)->name('customers.remove');
    //Route::get('/customers/{customer}/web-users', [IndexWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.index');
    //Route::get('/customers/{customer}/web-users/{webUser}', [ShowWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.show');
    //Route::get('/customers/{customer}/web-users/{webUser}/edit', [EditWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.edit');
});
