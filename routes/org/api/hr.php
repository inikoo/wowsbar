<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 15:20:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use App\Actions\HumanResources\ClockingMachine\UI\IndexClockingMachines;
use App\Actions\HumanResources\Workplace\UI\IndexWorkplaces;

Route::get('workplaces', IndexWorkplaces::class)->name('workplaces.index');

Route::get('clocking-machines', [IndexClockingMachines::class,'inOrganisation'])->name('clocking-machines.index');
