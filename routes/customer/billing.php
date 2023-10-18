<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Accounting\Billing\UI\CreateBilling;
use App\Actions\Accounting\Billing\UI\ShowBilling;
use App\Actions\UI\Customer\Billing\ShowBillingDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [
    'uses'  => ShowBillingDashboard::class,
    'icon'  => 'cash',
    'label' => 'billing'
])->name('dashboard');

Route::get('/create', [
    'uses'  => CreateBilling::class,
    'icon'  => 'cash',
    'label' => 'billing'
])->name('create');

Route::get('/{payment}', [
    'uses'  => ShowBilling::class,
    'icon'  => 'cash',
    'label' => 'billing'
])->name('show');
