<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\DownloadCustomersTemplate;
use App\Actions\CRM\Customer\UI\EditCustomer;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\CRM\Customer\UI\RemoveCustomer;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\CRM\Prospect\DownloadProspectsTemplate;
use App\Actions\CRM\Prospect\IndexProspects;
use App\Actions\CRM\User\UI\CreateUser;
use App\Actions\CRM\User\UI\EditUser;
use App\Actions\CRM\User\UI\IndexUsers;
use App\Actions\CRM\User\UI\ShowUser;
use App\Actions\Organisation\UI\CRM\CRMDashboard;

Route::get('/', [CRMDashboard::class, 'inOrganisation'])->name('dashboard');

Route::prefix('customers')->as('customers.')->group(function () {
    Route::get('/', IndexCustomers::class)->name('index');
    Route::get('/{customer}', [ShowCustomer::class, 'inOrganisation'])->name('show');
    Route::get('/{customer}/edit', [EditCustomer::class, 'inOrganisation'])->name('edit');
    Route::get('/{customer}/delete', RemoveCustomer::class)->name('remove');
    Route::get('/{customer}/web-users', [IndexUsers::class, 'inCustomer'])->name('show.web-users.index');
    Route::get('/customers/{customer}/web-users/create', [CreateUser::class, 'inCustomer'])->name('show.web-users.create');
    Route::get('/customers/{customer}/web-users/{user}', [ShowUser::class, 'inCustomer'])->name('show.web-users.show');
    Route::get('/customers/{customer}/web-users/{user}/edit', [EditUser::class, 'inCustomer'])->name('show.web-users.edit');
    Route::get('/uploads/template/download', DownloadCustomersTemplate::class)->name('uploads.template.download');
});

Route::prefix('prospects')->as('prospects.')->group(function () {
    Route::get('/', IndexProspects::class)->name('index');
    Route::get('/{prospect}', IndexProspects::class)->name('show');
    Route::get('/create', IndexProspects::class)->name('create');
    //Route::get('/customers/{customer}/delete', RemoveCustomer::class)->name('customers.remove');
    //Route::get('/customers/{customer}/web-users', [IndexWebUser::class, 'inCustomerinOrganisation'])->name('customers.show.web-users.index');
    //Route::get('/customers/{customer}/web-users/{webUser}', [ShowWebUser::class, 'inCustomerinOrganisation'])->name('customers.show.web-users.show');
    //Route::get('/customers/{customer}/web-users/{webUser}/edit', [EditWebUser::class, 'inCustomerinOrganisation'])->name('customers.show.web-users.edit');
    Route::get('/uploads/template/download', DownloadProspectsTemplate::class)->name('uploads.template.download');
});

Route::get('/shop/{shop}', [CRMDashboard::class, 'inShop'])->name('org.shops.show.dashboard');
Route::get('/shop/{shop}/customers', [IndexCustomers::class, 'inShop'])->name('org.shops.show.customers.index');
Route::get('/shop/{shop}/customers/{customer}', [ShowCustomer::class, 'inShop'])->name('org.shops.show.customers.show');
Route::get('/shop/{shop}/customers/{customer}/edit', [EditCustomer::class, 'inShop'])->name('org.shops.show.customers.edit');
Route::get('/shop/{shop}/prospects', [IndexProspects::class, 'inShop'])->name('org.shops.show.prospects.index');
