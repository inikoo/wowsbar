<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Dec 2023 23:37:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Services\Aurora;

use Illuminate\Support\Facades\DB;

class FetchAuroraWebpage extends FetchAurora
{
    /**
     * @throws \Exception
     */
    protected function parseModel(): void
    {
        $this->parsedData =
            [

                'title' => $this->auroraModelData->{'Webpage Name'},
                'url'   => $this->auroraModelData->{'Webpage URL'},
            ];
    }


    protected function fetchData($id): object|null
    {
        return DB::connection('aurora')
            ->table('Page Store Dimension')
            ->where('Page Key', $id)->first();
    }
}
