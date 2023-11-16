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
use App\Actions\Helpers\Tag\UI\CreateTag;
use App\Actions\Helpers\Tag\UI\EditTag;
use Illuminate\Support\Facades\Route;

Route::get('/', ['icon'  => 'thumbs-up', 'label' => 'queries'])->uses(IndexQuery::class)->name('index');
Route::get('/{tag}', ['icon'  => 'thumbs-up', 'label' => 'show tag'])->uses(ShowQuery::class)->name('show');
Route::get('/{tag}/edit', ['icon'  => 'thumbs-up', 'label' => 'edit tag'])->uses(EditTag::class)->name('edit');
Route::get('/create', ['icon'  => 'thumbs-up', 'label' => 'create tag'])->uses(CreateTag::class)->name('create');
