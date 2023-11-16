<?php

use App\Actions\Helpers\Tag\GetTagOptions;
use Illuminate\Support\Facades\Route;

Route::get('tags', GetTagOptions::class)->name('tags');
