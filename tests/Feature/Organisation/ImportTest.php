<?php

use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use App\Models\Leads\Prospect;
use Illuminate\Support\Facades\Artisan;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('import employees', function () {
    Artisan::call("employee:import database/seeders/uploads/local/employees.xlsx");
    expect(Employee::count())->toBe(0);
});

test('import guests', function () {
    Artisan::call("guest:import database/seeders/uploads/local/guests.xlsx");
    expect(Guest::count())->toBe(0);
});

test('import prospects', function () {
    Artisan::call("shop:import-prospects awa database/seeders/uploads/local/prospects.xlsx");
    expect(Prospect::count())->toBe(0);
})->todo();
