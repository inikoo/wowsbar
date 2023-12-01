<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 29 Nov 2023 12:29:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Models\Assets\Country;
use App\Models\Assets\Timezone;
use App\Models\Assets\Language;
use App\Models\Assets\Currency;
use App\Actions\Helpers\CurrencyExchange\GetCurrencyExchange;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeAll(fn () => loadDB('test_base_database.dump'));

it('has countries', function (string $countryCode) {
    $country= Country::where('code', $countryCode)->firstOrFail();
    expect($country->id)->toBeInt();
})->with([
    'GB','ES','fr','De'
]);

it('is no countries with code', function (string $countryCode) {
    expect(function () use ($countryCode) {
        Country::where('code', $countryCode)->firstOrFail();
    })->toThrow(ModelNotFoundException::class);
})->with([
    'XX','ZZ',''
]);

it('has timezones', function (string $timezone) {
    $timezone = Timezone::where('name', $timezone)->firstOrFail();
    expect($timezone->id)->toBeInt();
})->with([
    'Asia/Jakarta','Pacific/Norfolk'
]);

it('is no timezones with code', function (string $countryCode) {
    expect(function () use ($countryCode) {
        Timezone::where('name', $countryCode)->firstOrFail();
    })->toThrow(ModelNotFoundException::class);
})->with([
    'XX', ''
]);

it('has languages', function (string $language) {
    $language = Language::where('code', $language)->firstOrFail();
    expect($language->id)->toBeInt();
})->with([
    'en-us', 'id', 'zu'
]);

it('is no languages with code', function (string $language) {
    expect(function () use ($language) {
        Language::where('name', $language)->firstOrFail();
    })->toThrow(ModelNotFoundException::class);
})->with([
    'xx', ''
]);


it('has currencies', function (string $currency) {
    $currency = Currency::where('code', $currency)->firstOrFail();
    expect($currency->id)->toBeInt();
})->with([
    'IDR', 'AFN', 'ZWL'
]);

it('is no currency with code', function (string $currency) {
    expect(function () use ($currency) {
        Currency::where('code', $currency)->firstOrFail();
    })->toThrow(ModelNotFoundException::class);
})->with([
    'XXX', ''
]);

it('currency exchange test', function () {
    $baseCurrency   = Currency::where('code', 'IDR')->firstOrFail();
    $targetCurrency = Currency::where('code', 'USD')->firstOrFail();

    $currencyExchange = GetCurrencyExchange::run($baseCurrency, $targetCurrency);
    expect($currencyExchange)->toBeFloat();
})->todo();
