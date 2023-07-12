<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Tue, 14 Mar 2023 10:25:11 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */


use App\Actions\Auth\User\UpdateUser;
use App\Actions\Tenancy\Tenant\UpdateSystemSettings;
use App\Actions\UI\Profile\UpdateProfile;
use App\Actions\Web\Website\DeleteWebsite;
use App\Actions\Web\Website\StoreWebsite;
use App\Actions\Web\Website\UpdateWebsite;

Route::post('/website/', StoreWebsite::class)->name('website.store');
Route::patch('/website/{website}', UpdateWebsite::class)->name('website.update');
Route::delete('/website/{website}', DeleteWebsite::class)->name('website.delete');


Route::patch('/user/{user}', UpdateUser::class)->name('user.update');
Route::patch('/profile', UpdateProfile::class)->name('profile.update');


Route::patch('/system-settings', UpdateSystemSettings::class)->name('system-settings.update');
