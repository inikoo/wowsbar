<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Portfolio\PortfolioWebsite\UI\IndexProspectsPortfolioWebsites;
use App\Actions\UI\Customer\Prospects\ShowProspectsDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [
    'uses'  => ShowProspectsDashboard::class,
    'icon'  => 'briefcase',
    'label' => 'leads'
])->name('dashboard');

Route::get('/websites', [
    'uses'  => IndexProspectsPortfolioWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
