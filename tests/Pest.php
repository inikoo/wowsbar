<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:40:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\Market\Shop\StoreShop;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Web\Website\StoreWebsite;
use App\Enums\Marketing\Shop\ShopTypeEnum;
use App\Models\CRM\Customer;
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
function createCustomer(): void
{
    try {
        $organisation = organisation();
    } catch (Exception) {
        $organisation = StoreOrganisation::make()->action(Organisation::factory()->definition());
        $shop         = StoreShop::run(
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
    $customer = Customer::first();
    if (!$customer) {
        $customer = StoreCustomer::make()->action($organisation->shops->first(), Customer::factory()->definition());
    }
    config(['global.customer_id' => $customer->id]);
}
