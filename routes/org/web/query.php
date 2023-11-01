<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:11:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Query\UI\CreateQuery;
use App\Actions\Helpers\Query\UI\EditQuery;
use App\Actions\Helpers\Query\UI\IndexQuery;
use App\Actions\Helpers\Query\UI\ShowQuery;
use Illuminate\Support\Facades\Route;

Route::get('/', ['icon'  => 'thumbs-up', 'label' => 'queries'])->uses(IndexQuery::class)->name('index');
Route::get('/{query}', ['icon'  => 'thumbs-up', 'label' => 'show query'])->uses(ShowQuery::class)->name('show');
Route::get('/{query}/edit', ['icon'  => 'thumbs-up', 'label' => 'edit query'])->uses(EditQuery::class)->name('edit');
Route::get('/create', ['icon'  => 'thumbs-up', 'label' => 'create query'])->uses(CreateQuery::class)->name('create');
