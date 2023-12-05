<?php

use App\Actions\Helpers\Tag\GetTagOptions;
use App\Actions\Leads\Prospect\GetProspectOptions;
use App\Actions\Mail\EmailTemplate\GetEmailTemplateOptions;
use Illuminate\Support\Facades\Route;

Route::get('tags', GetTagOptions::class)->name('tags');
Route::get('prospects', GetProspectOptions::class)->name('prospects');
Route::get('email/templates', GetEmailTemplateOptions::class)->name('email.templates');
