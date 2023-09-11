<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\UI\CRM\CRMDashboard;

Route::get('/', [CRMDashboard::class, 'inTenant'])->name('dashboard');
//Route::get('/customers', [IndexCustomers::class, 'inTenant'])->name('customers.index');
//Route::get('/customers/{customer}', [ShowCustomer::class, 'inTenant'])->name('customers.show');
//Route::get('/customers/{customer}/edit', [EditCustomer::class, 'inTenant'])->name('customers.edit');
//Route::get('/customers/{customer}/delete', RemoveCustomer::class)->name('customers.remove');
//Route::get('/customers/{customer}/web-users', [IndexWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.index');
//Route::get('/customers/{customer}/web-users/{webUser}', [ShowWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.show');
//Route::get('/customers/{customer}/web-users/{webUser}/edit', [EditWebUser::class, 'inCustomerInTenant'])->name('customers.show.web-users.edit');


