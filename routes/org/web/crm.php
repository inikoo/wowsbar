<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\UI\EditCustomer;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\CRM\Customer\UI\RemoveCustomer;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\CRM\User\UI\CreateUser;
use App\Actions\CRM\User\UI\EditUser;
use App\Actions\CRM\User\UI\IndexUsers;
use App\Actions\CRM\User\UI\ShowUser;
use App\Actions\Leads\Prospect\RemoveProspect;
use App\Actions\Leads\Prospect\UI\EditProspect;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Leads\Prospect\UI\ShowProspect;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Actions\Organisation\UI\CRM\ShowMailroomDashboard;

Route::get('/', [ShowCRMDashboard::class, 'inOrganisation'])->name('dashboard');

Route::get('customers', IndexCustomers::class)->name('customers.index');

Route::prefix('customers/{customer}')->as('customers.')->group(function () {
    Route::get('', ShowCustomer::class)->name('show');
    Route::get('edit', [EditCustomer::class, 'inOrganisation'])->name('edit');
    Route::get('delete', RemoveCustomer::class)->name('remove');
    Route::get('web-users', [IndexUsers::class, 'inCustomer'])->name('show.web-users.index');
    Route::get('web-users/create', [CreateUser::class, 'inCustomer'])->name('show.web-users.create');
    Route::get('web-users/{user}', [ShowUser::class, 'inCustomer'])->name('show.web-users.show');
    Route::get('web-users/{user}/edit', [EditUser::class, 'inCustomer'])->name('show.web-users.edit');
});

Route::prefix('prospects')->as('prospects.')->group(function () {
    Route::get('/', IndexProspects::class)->name('index');
    Route::get('/{prospect}', IndexProspects::class)->name('show');
    Route::get('/{prospect}/delete', RemoveProspect::class)->name('remove');
});

Route::prefix('shop/{shop}')->as('shop.')->group(function () {
    Route::get('/', [ShowCRMDashboard::class, 'inShop'])->name('dashboard');

    Route::get('customers', [IndexCustomers::class, 'inShop'])->name('customers.index');
    Route::get('customers/create', [IndexCustomers::class, 'inShop'])->name('customers.create');

    Route::prefix('customers/{customer}')->as('customers.')->group(function () {
        Route::get('', [ShowCustomer::class, 'inShop'])->name('show');
        Route::get('/edit', [EditCustomer::class, 'inShop'])->name('edit');
        Route::get('/web-users', [IndexUsers::class, 'inCustomerInShop'])->name('show.web-users.index');
        Route::get('/web-users/create', [CreateUser::class, 'inCustomerInShop'])->name('show.web-users.create');
        Route::get('/web-users/{user}', [ShowUser::class, 'inCustomerInShop'])->name('show.web-users.show');
        Route::get('/web-users/{user}/edit', [EditUser::class, 'inCustomerInShop'])->name('show.web-users.edit');
    });

    Route::prefix('prospects')->as('prospects.')->group(function () {
        Route::get('/', [IndexProspects::class, 'inShop'])->name('index');
        Route::get('/create', [IndexProspects::class, 'inShop'])->name('create');
        Route::get('/{prospect}', [ShowProspect::class, 'inShop'])->name('show');
        Route::get('/{prospect}/edit', [EditProspect::class, 'inShop'])->name('edit');
        Route::get('/{prospect}/delete', [RemoveProspect::class, 'inShop'])->name('remove');
    });

    Route::get('mailroom', ShowMailroomDashboard::class)->name('mailroom.dashboard');
});
