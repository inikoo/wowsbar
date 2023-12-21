<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Services;

use App\Services\Aurora\FetchAuroraProspect;
use App\Services\Aurora\FetchAuroraWebpage;
use Illuminate\Support\Facades\DB;

class AuroraService implements SourceService
{
    public function initialisation(string $databaseName = ''): void
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $databaseName);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');

    }


    public function fetchProspect($id): ?array
    {
        return (new FetchAuroraProspect($this))->fetch($id);
    }

    public function fetchWebpage($id): ?array
    {
        return (new FetchAuroraWebpage($this))->fetch($id);
    }


}
