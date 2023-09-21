<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\StoreUser;
use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Web\Website\StoreWebsite;
use App\Enums\Organisation\Market\Shop\ShopTypeEnum;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Web\Website;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('create organisation', function () {
    $modelData    = Organisation::factory()->definition();
    $organisation = StoreOrganisation::make()->action($modelData);
    expect($organisation)->toBeInstanceOf(Organisation::class);

    return $organisation;
});

test('create shop', function () {
    $shop = StoreShop::make()->action(
        [
            'code' => 'acme',
            'name' => 'Acme inc',
            'type' => ShopTypeEnum::DIGITAL_MARKETING->value
        ]
    );
    expect($shop)->toBeInstanceOf(Shop::class);

    return $shop;
})->depends('create organisation');

test('create website', function ($shop) {
    $website = StoreWebsite::make()->action(
        $shop,
        [
            'code'   => $shop->code,
            'domain' => 'acme.test',
        ]
    );

    $shop->refresh();

    expect($website)->toBeInstanceOf(Website::class)
        ->and($shop->website)->toBeInstanceOf(Website::class);
})->depends('create shop');


test('create customer', function ($shop) {
    $modelData = Customer::factory()->definition();
    $customer  = StoreCustomer::make()->action($shop, $modelData);
    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($shop->crmStats->number_customers)->toBe(1)
        ->and(organisation()->crmStats->number_customers)->toBe(1);

    return $customer;
})->depends('create shop');

test('create customer user', function ($customer) {
    Config::set('global.customer_id', $customer->id);


    $user = StoreUser::make()->action(
        $customer->shop->website,
        $customer,
        [
            'username'     => 'aiku',
            'contact_name' => 'Alex',
            'email'        => $customer->email,
            'password'     => fake()->password
        ]
    );
    expect($user)->toBeInstanceOf(User::class);
})->depends('create customer');
