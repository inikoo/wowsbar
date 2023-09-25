<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CustomerWebsites\CustomerWebsite\UI\EditCustomerWebsite;
use App\Actions\CustomerWebsites\CustomerWebsite\UI\IndexCustomerWebsites;
use App\Actions\CustomerWebsites\CustomerWebsite\UI\RemoveCustomerWebsite;
use App\Actions\CustomerWebsites\CustomerWebsite\UI\ShowCustomerWebsite;
use App\Actions\UI\Organisation\CustomerWebsites\ShowCustomerWebsitesDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => ShowCustomerWebsitesDashboard::class, 'icon' => 'user-hard-hat', 'label' => 'human resources'])->name('dashboard');
Route::get('/customer-websites/', ['uses' => IndexCustomerWebsites::class, 'icon' => 'portfolio', 'label' => "customer's websites"])->name('index');

Route::prefix('shop/{shop}')->as('shop.')->group(function () {
    Route::get('/', ['icon' => 'portfolio', 'label' => "customer's websites"])->uses([ShowCustomerWebsitesDashboard::class,'inShop'])->name('dashboard');
    Route::get('/customer-websites/', ['icon' => 'globe', 'label' => 'websites'])->uses([IndexCustomerWebsites::class,'inShop'])->name('customer-websites.index');
    // Route::get('/customer-websites/{customerWebsite}/edit', [EditCustomerWebsite::class,'inShop'])->name('edit');
    // Route::get('/customer-websites/{customerWebsite}/delete', [RemoveCustomerWebsite::class,'inShop'])->withTrashed()->name('remove');
    // Route::get('/customer-websites/{customerWebsite}', ['uses' => [ShowCustomerWebsite::class,'inShop'], 'icon' => 'globe', 'label' => 'websites'])->name('show');
});


/*
Route::get('/{customerWebsite}/banners/create', [CreateBanner::class, 'inCustomerWebsite'])->name('show.banners.create');
Route::get('/{customerWebsite}/banners', [IndexBanners::class, 'inCustomerWebsite'])->name('show.banners.index');


Route::get('/{customerWebsite}/banners/{banner}', [
    'uses'  => ShowBanner::class . '@inCustomerWebsite',
    'icon'  => 'globe',
    'label' => 'banner'
])->name('show.banners.show');

Route::get('/{customerWebsite}/banners/{banner}/edit', [
    'uses'  => EditBanner::class,
    'icon'  => 'globe',
    'label' => 'banner'
])->name('show.banners.edit');

Route::get('/{customerWebsite}/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inCustomerWebsite'])->name('show.banners.workshop');
Route::post('/{customerWebsite}/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBannerInCustomerWebsite'])->name('show.banners.workshop.images.store');
Route::get('/{customerWebsite}/banners/{banner}/delete', [RemoveBanner::class, 'inCustomerWebsite'])->withTrashed()->name('show.banners.remove');
Route::get('/banners', [IndexBanners::class, 'inCustomer'])->name('banners.index');

Route::get('/{customerWebsite}/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('snapshots.index');
Route::get('/{customerWebsite}/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('snapshots.show');
*/
