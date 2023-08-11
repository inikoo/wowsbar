<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Tue, 14 Mar 2023 10:25:11 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */


use App\Actions\Auth\User\UpdateUser;
use App\Actions\Gallery\UpdateUploadedImage;
use App\Actions\Portfolio\ContentBlock\DeleteContentBlock;
use App\Actions\Portfolio\ContentBlock\StoreContentBlock;
use App\Actions\Portfolio\ContentBlock\UpdateContentBlock;
use App\Actions\Portfolio\ContentBlock\UpdateStateContentBlock;
use App\Actions\Tenancy\Tenant\UpdateSystemSettings;
use App\Actions\UI\Profile\UpdateProfile;
use App\Actions\Portfolio\Website\DeleteWebsite;
use App\Actions\Portfolio\Website\StoreWebsite;
use App\Actions\Portfolio\Website\UpdateWebsite;

Route::post('/website/', StoreWebsite::class)->name('website.store');
Route::patch('/website/{website}', UpdateWebsite::class)->name('website.update');
Route::delete('/website/{website}', DeleteWebsite::class)->name('website.delete');
Route::post('/website/{website}/web-block-type/{webBlockType}/banners', [StoreContentBlock::class,'inWebsiteInWebBlockType'])->name('website.web-block-type.banner.store');
Route::patch('/content-block/{contentBlock}', UpdateContentBlock::class)->name('content-block.update');
Route::patch('/content-block/{contentBlock}/state/{state}', UpdateStateContentBlock::class)->name('content-block.updateState');
Route::delete('/content-block/{contentBlock}', DeleteContentBlock::class)->name('content-block.delete');

Route::patch('/images/{media}', UpdateUploadedImage::class)->name('images.update');

Route::patch('/user/{user:username}', UpdateUser::class)->name('user.update');
Route::patch('/profile', UpdateProfile::class)->name('profile.update');


Route::patch('/system-settings', UpdateSystemSettings::class)->name('system-settings.update');
