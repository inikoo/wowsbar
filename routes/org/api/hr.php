<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 15:20:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */


use App\Actions\HumanResources\Clocking\StoreClockingFromClockingMachine;
use App\Actions\HumanResources\ClockingMachine\StoreClockingMachine;
use App\Actions\HumanResources\ClockingMachine\UI\IndexClockingMachines;
use App\Actions\HumanResources\ClockingMachine\UI\ShowClockingMachine;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\HumanResources\Workplace\UI\IndexWorkplaces;
use App\Actions\HumanResources\Workplace\UI\ShowWorkplace;
use App\Actions\HumanResources\Workplace\UpdateWorkplace;

Route::get('workplaces', IndexWorkplaces::class)->name('workplaces.index');
Route::post('workplaces', StoreWorkplace::class)->name('workplaces.store');
Route::patch('workplaces/{workplace:id}', UpdateWorkplace::class)->name('workplaces.update');
Route::get('workplaces/{workplace:id}', ShowWorkplace::class)->name('workplaces.show');
Route::get('workplaces/{workplace:id}/clocking-machines', [IndexClockingMachines::class,'inWorkplace'])->name('workplaces.show.clocking-machines.index');
Route::post('workplaces/{workplace:id}/clocking-machines', StoreClockingMachine::class)->name('workplaces.show.clocking-machines.store');

Route::get('clocking-machines', [IndexClockingMachines::class,'inOrganisation'])->name('clocking-machines.index');
Route::get('clocking-machines/{clockingMachine:id}', [ShowClockingMachine::class,'inOrganisation'])->name('clocking-machines.show');
Route::post('clocking-machines/{clockingMachine:id}/clockings', StoreClockingFromClockingMachine::class)->name('clocking-machines.show.clockings.store');

Route::post('clockings', [StoreClockingFromClockingMachine::class, 'asFromNfcTag'])->name('clocking-machines.clockings.store');
