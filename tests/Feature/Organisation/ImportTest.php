<?php

use App\Models\Auth\Guest;
use App\Models\Helpers\Upload;
use App\Models\HumanResources\Employee;
use App\Models\Leads\Prospect;
use Illuminate\Support\Facades\Artisan;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('import employees', function () {
    Artisan::call("employee:import database/seeders/uploads/local/employees.xlsx");
    expect(Upload::where('type', class_basename(Employee::class))->sum('number_rows'))->toBe(10);
});

test('import guests', function () {
    Artisan::call("guest:import database/seeders/uploads/local/guests.xlsx");
    expect(Upload::where('type', class_basename(Guest::class))->sum('number_rows'))->toBe(10);
});

test('import prospects', function () {
    list(
        $this->organisation,
        $this->organisationUser,
        $this->shop
    ) = createShop();

    Artisan::call("shop:import-prospects ".$this->shop->slug." database/seeders/uploads/local/prospects.xlsx");
    expect(Upload::where('type', class_basename(Prospect::class))->sum('number_rows'))->toBe(5);
});
