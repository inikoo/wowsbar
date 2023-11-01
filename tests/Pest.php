<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:40:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Organisation\Guest\StoreGuest;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Web\Website\StoreWebsite;
use App\Enums\Market\Shop\ShopTypeEnum;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\Auth\OrganisationUser;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Symfony\Component\Process\Process;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

function loadDB($dumpName): void
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../', '.env.testing');
    $dotenv->load();

    $process = new Process(
        [
            __DIR__.'/../devops/devel/reset_test_database.sh',
            env('DB_DATABASE_TEST', 'wowsbar_test'),
            env('DB_PORT'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            $dumpName
        ]
    );
    $process->run();
}


/**
 * @throws \Throwable
 */
function createCustomer(): array
{
    [$organisation,$organisationUser,$shop] = createShop();
    $customer                               = Customer::first();
    if (!$customer) {
        $customer = StoreCustomer::make()->action($organisation->shops->first(), Customer::factory()->definition());
    }
    config(['global.customer_id' => $customer->id]);
    return[
        $organisation,$organisationUser,$shop,$customer
    ];
}

function createShop(): array
{
    try {
        $organisation    = organisation();
        $shop            =Shop::first();
        $organisationUser=OrganisationUser::first();
    } catch (Exception) {
        $organisation = StoreOrganisation::make()->action(Organisation::factory()->definition());


        $organisationUserData = array_merge(
            Guest::factory()->definition(),
            [
                'positions' => ['admin'],
                'type'      => GuestTypeEnum::EXTERNAL_ADMINISTRATOR->value
            ]
        );


        $guest           =StoreGuest::make()->action($organisationUserData);
        $organisationUser=$guest->organisationUser;

        $shop = StoreShop::run(
            $organisation,
            [
                'type' => ShopTypeEnum::DIGITAL_MARKETING->value
            ]
        );

        StoreWebsite::make()->action(
            $shop,
            [
                'code'   => $shop->code,
                'domain' => 'acme.test',
            ]
        );
        $shop->refresh();
    }

    return [
        $organisation,
        $organisationUser,
        $shop
    ];
}
