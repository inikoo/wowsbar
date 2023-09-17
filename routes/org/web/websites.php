<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Actions\Organisation\Web\Webpage\UI\CreateArticle;
use App\Actions\Organisation\Web\Webpage\UI\CreateWebpage;
use App\Actions\Organisation\Web\Webpage\UI\EditWebpage;
use App\Actions\Organisation\Web\Webpage\UI\ShowWebpage;
use App\Actions\Organisation\Web\Webpage\UI\ShowWebpageWorkshop;
use App\Actions\Organisation\Web\Webpage\UI\ShowWebpageWorkshopPreview;
use App\Actions\Organisation\Web\Website\UI\EditWebsite;
use App\Actions\Organisation\Web\Website\UI\IndexWebsites;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshop;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshopPreview;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexWebsites::class)->name('index');
Route::get('/{website}', ShowWebsite::class)->name('show');
Route::get('/{website}/edit', EditWebsite::class)->name('edit');
Route::get('/{website}/workshop', ShowWebsiteWorkshop::class)->name('workshop');
Route::get('/{website}/workshop/preview', ShowWebsiteWorkshopPreview::class)->name('preview');
Route::get('/{website}/blog/article/create', CreateArticle::class)->name('show.blog.article.create');

Route::get('/{website}/webpages/create', CreateWebpage::class)->name('show.webpages.create');
Route::get('/{website}/webpages', [IndexWebpages::class, 'inWebsite'])->name('show.webpages.index');


Route::get('/{website}/webpages/{webpage}/create', [CreateWebpage::class, 'inWebsiteInWebpage'])->name('show.webpages.show.webpages.create');
Route::get('/{website}/webpages/{webpage}/webpages', [IndexWebpages::class, 'inWebpage'])->name('show.webpages.show.webpages.index');
Route::get('/{website}/webpages/{webpage}/edit', [EditWebpage::class, 'inWebsite'])->name('show.webpages.edit');
Route::get('/{website}/webpages/{webpage}/workshop', [ShowWebpageWorkshop::class, 'inWebsite'])->name('show.webpages.workshop');
Route::get('/{website}/webpages/{webpage}/workshop/preview', [ShowWebpageWorkshopPreview::class, 'inWebsite'])->name('show.webpages.preview');
Route::get('/{website}/webpages/{webpage}', [ShowWebpage::class, 'inWebsite'])->name('show.webpages.show');
