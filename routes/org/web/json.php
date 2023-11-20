<?php

use App\Actions\Helpers\Tag\GetTagOptions;
use App\Actions\Leads\Prospect\GetProspectOptions;
use Illuminate\Support\Facades\Route;

Route::get('tags', GetTagOptions::class)->name('tags');
Route::get('prospects', GetProspectOptions::class)->name('prospects');
