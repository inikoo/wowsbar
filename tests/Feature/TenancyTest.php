<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\CRM\Customer\StoreCustomer;
use App\Actions\Organisation\CRM\PublicUser\StorePublicUser;
use App\Actions\Organisation\Market\Shop\StoreShop;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Enums\Organisation\Market\Shop\ShopTypeEnum;
use App\Models\CRM\Customer;
use App\Models\CRM\PublicUser;
use App\Models\Organisation\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Tenancy\Tenant;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('create organisation', function () {
    $modelData     = Organisation::factory()->definition();
    $organisation  = StoreOrganisation::make()->action($modelData);
    expect($organisation)->toBeInstanceOf(Organisation::class);
    return $organisation;
});

test('create shop', function () {
    $shop  = StoreShop::make()->action(
        [
            'code'=>'acme',
            'name'=>'Acme inc',
            'type'=>ShopTypeEnum::DIGITAL_MARKETING->value
        ]
    );
    expect($shop)->toBeInstanceOf(Shop::class);
    return $shop;
})->depends('create organisation');

test('create customer', function ($shop) {
    $modelData = Customer::factory()->definition();
    $customer  = StoreCustomer::make()->action($shop, $modelData);
    expect($customer)->toBeInstanceOf(Customer::class);
    return $customer;
})->depends('create shop');

test('create customer user', function ($customer) {
    $publicUser  = StorePublicUser::make()->action(
        $customer,
        [
            'email'   => $customer->email,
            'password'=> fake()->password
        ]
    );
    expect($publicUser)->toBeInstanceOf(PublicUser::class);

})->depends('create customer');

test('create tenant', function ($customer) {
    $modelData = Tenant::factory()->definition();
    $tenant    = StoreTenant::make()->action($customer, $modelData);
    expect($tenant)->toBeInstanceOf(Tenant::class);
    $tenant->makeCurrent();

    //$user = $tenant->users()->first();
    //expect($user)->toBeInstanceOf(User::class);

    //        ->and($user->getMedia('avatar')->first())->toBeInstanceOf(Media::class) // TODO Need To Fix
    //        ->and($user->avatar)->toBeInstanceOf(App\Models\Media\Media::class);

})->depends('create customer');
