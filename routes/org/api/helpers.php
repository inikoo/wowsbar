<?php

use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Models\Assets\Country;
use Spatie\LaravelOptions\Options;

Route::get('workplace-types', function () {
    return Options::forEnum(WorkplaceTypeEnum::class)->toArray();
})->name('workplace.types');

Route::get('countries', function () {
    return Options::forModels(Country::class)->toArray();
})->name('countries');
