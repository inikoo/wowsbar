<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:11:46 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\CRM\CustomerBanners\UI\IndexCustomerBanners;
use App\Actions\CRM\CustomerBanners\UI\ShowBanner;
use App\Actions\CRM\CustomerWebsite\UI\IndexCaasCustomerWebsites;
use App\Actions\UI\Organisation\Catalogue\ShowCaaSDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/caas/dashboard');})->name('root');
Route::get('/dashboard', ['icon'  => 'globe', 'label' => 'Caas'])->uses(ShowCaaSDashboard::class)->name('dashboard');

Route::get('/websites', ['icon'  => 'globe', 'label' => 'websites'])->uses(IndexCaasCustomerWebsites::class)->name('websites.index');
Route::get('/banners', ['icon'  => 'globe', 'label' => 'banners'])->uses(IndexCustomerBanners::class)->name('banners.index');
Route::get('/banners/{banner}', ['icon'  => 'globe', 'label' => 'Caas'])->uses(ShowBanner::class)->name('banners.show');
//todo Route::get('/banners/{banner}', ['icon'  => 'globe', 'label' => 'banners'])->uses(IndexCustomerBanners::class)->name('banners.show');
