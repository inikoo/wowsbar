<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Actions\Organisation\Web\Webpage\UI\CreateArticle;
use App\Actions\Organisation\Web\Webpage\UI\CreateWebpage;
use App\Actions\Organisation\Web\Webpage\UI\ShowWebpage;
use App\Actions\Organisation\Web\Webpage\UI\ShowWebpageWorkshop;
use App\Actions\Organisation\Web\Website\UI\CreateWebsite;
use App\Actions\Organisation\Web\Website\UI\EditWebsite;
use App\Actions\Organisation\Web\Website\UI\IndexWebsites;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshop;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshopPreview;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexWebsites::class)->name('index');


/*

Route::get('/dashboard', ShowWebsite::class)->name('show');
Route::get('/create', CreateWebsite::class)->name('create');
Route::get('/edit', EditWebsite::class)->name('edit');
Route::get('/workshop', ShowWebsiteWorkshop::class)->name('workshop');
Route::get('/workshop/preview', ShowWebsiteWorkshopPreview::class)->name('preview');
Route::get('/webpages', IndexWebpages::class)->name('webpages.index');
Route::get('/webpages/create', CreateWebpage::class)->name('webpages.create');
Route::get('/blog/article/create', CreateArticle::class)->name('blog.article.create');

Route::get('/webpages/{webpage}/webpages/create', [CreateWebpage::class,'inWebpage'])->name('webpages.show.webpages.create');
Route::get('/webpages/{webpage}/webpages', [IndexWebpages::class,'inWebpage'])->name('webpages.show.webpages.index');


Route::get('/webpages/{webpage}', ShowWebpage::class)->name('webpages.show');

Route::get('/webpages/{webpage}/edit', EditWebsite::class)->name('webpages.edit');
Route::get('/webpages/{webpage}/workshop', ShowWebpageWorkshop::class)->name('webpages.workshop');
Route::get('/webpages/{webpage}/workshop/preview', ShowWebsiteWorkshopPreview::class)->name('webpages.preview');
*/
