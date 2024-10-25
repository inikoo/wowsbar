<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Snapshot\UI\IndexSnapshots;
use App\Actions\Helpers\Snapshot\UI\ShowSnapshot;
use App\Actions\Portfolio\Announcement\UI\ShowAnnouncement;
use App\Actions\Portfolio\AnnouncementTemplate\UI\IndexAnnouncementTemplates;
use App\Actions\Portfolio\Banner\UI\CreateBanner;
use App\Actions\Portfolio\Banner\UI\DuplicateBanner;
use App\Actions\Portfolio\Banner\UI\EditBanner;
use App\Actions\Portfolio\Announcement\UI\IndexAnnouncement;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Portfolio\Banner\UI\RemoveBanner;
use App\Actions\Portfolio\Banner\UI\ShowBanner;
use App\Actions\Portfolio\Banner\UI\ShowBannerWorkshop;
use App\Actions\Portfolio\Banner\UI\ShowDeletedBanner;
use App\Actions\Portfolio\Gallery\DeleteUploadedImage;
use App\Actions\Portfolio\Gallery\UI\ShowGallery;
use App\Actions\Portfolio\Gallery\UI\StockImages\IndexStockImages;
use App\Actions\Portfolio\Gallery\UI\StockImages\ShowStockImage;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\EditUploadedImage;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\ShowUploadedImage;
use App\Actions\Portfolio\PortfolioWebsite\UI\IndexBannersPortfolioWebsites;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowBannersPortfolioWebsite;
use App\Actions\UI\Customer\Banners\ShowBannersDashboard;
use App\Actions\Web\Website\ShowFooter;
use App\Actions\Web\Website\ShowFooterPreview;
use Illuminate\Support\Facades\Route;

Route::name('dashboard')->group(function () {
    Route::get('/dashboard', ['icon' => 'rectangle-wide', 'label' => 'banners dashboard'])->uses(ShowBannersDashboard::class);
});


Route::name('banners.')->prefix('cms')->group(function () {
    Route::get('', [IndexBanners::class, 'inCustomer'])->name('index');
    Route::get('/create', [CreateBanner::class, 'inCustomer'])->name('create');
    Route::prefix('{banner}')->group(function () {
        Route::get('', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBanner::class)->name('show');
        Route::get('edit', ['icon' => 'globe', 'label' => 'banner'])->uses(EditBanner::class)->name('edit');
        Route::get('workshop', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBannerWorkshop::class)->name('workshop');
        Route::get('delete', ['icon' => 'globe', 'label' => 'banner'])->uses(RemoveBanner::class)->name('remove');
        Route::get('deleted', ['icon' => 'globe', 'label' => 'banner'])->withTrashed()->uses(ShowDeletedBanner::class)->name('deleted');
        Route::get('duplicate', DuplicateBanner::class)->name('duplicate');
        Route::get('snapshots', [IndexSnapshots::class, 'inBanner'])->name('show.snapshots.index');
        Route::get('snapshots/{snapshot}', [ShowSnapshot::class, 'inBanner'])->name('show.snapshots.show');
    });
});

Route::name('announcement.')->prefix('announcement')->group(function () {
    Route::get('', [IndexAnnouncement::class, 'inCustomer'])->name('index');
    Route::get('{announcement:ulid}', [ShowAnnouncement::class, 'inCustomer'])->name('show');
    Route::get('templates', IndexAnnouncementTemplates::class)->name('templates.index');
    // Route::get('/create', [CreateBanner::class, 'inCustomer'])->name('create');
    // Route::prefix('{banner}')->group(function () {
    //     Route::get('', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBanner::class)->name('show');
    //     Route::get('edit', ['icon' => 'globe', 'label' => 'banner'])->uses(EditBanner::class)->name('edit');
    //     Route::get('workshop', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBannerWorkshop::class)->name('workshop');
    //     Route::get('delete', ['icon' => 'globe', 'label' => 'banner'])->uses(RemoveBanner::class)->name('remove');
    //     Route::get('deleted', ['icon' => 'globe', 'label' => 'banner'])->withTrashed()->uses(ShowDeletedBanner::class)->name('deleted');
    //     Route::get('duplicate', DuplicateBanner::class)->name('duplicate');
    //     Route::get('snapshots', [IndexSnapshots::class, 'inBanner'])->name('show.snapshots.index');
    //     Route::get('snapshots/{snapshot}', [ShowSnapshot::class, 'inBanner'])->name('show.snapshots.show');
    // });
});

Route::name('workshop.')->prefix('workshop')
    ->group(function () {
        Route::name('footers.')->prefix('footers')->group(function () {
            Route::get('', ShowFooter::class)->name('index');
            Route::get('preview', ShowFooterPreview::class)->name('preview');
        });
    });

Route::prefix('websites')->name('websites')->group(function () {
    Route::get('/websites', ['icon' => 'globe', 'label' => 'websites'])->uses(IndexBannersPortfolioWebsites::class)->name('.index');
    Route::prefix('{portfolioWebsite}')->group(function () {
        Route::get('', ['icon' => 'globe', 'label' => 'websites'])->uses(ShowBannersPortfolioWebsite::class)->name('.show');
    });
});

Route::prefix('gallery')->name('gallery')->group(function () {
    Route::get('/', ShowGallery::class);
    Route::prefix('uploaded-images')->name('.uploaded-images')->group(function () {
        Route::get('', IndexUploadedImages::class)->name('.index');
        Route::get('{media}', ShowUploadedImage::class)->name('.show');
        Route::get('{media}/edit', EditUploadedImage::class)->name('.edit');
        Route::get('{media}/delete', DeleteUploadedImage::class)->name('.remove');
    });
    Route::prefix('stock-images')->name('.stock-images')->group(function () {
        Route::get('', IndexStockImages::class)->name('.index');
        Route::get('{media}', ShowStockImage::class)->name('.show');
    });
});
