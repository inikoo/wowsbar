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


        $url= $this->auroraModelData->{'Webpage URL'};
        $url= str_replace('https://', '', $url);
        $url= str_replace($this->auroraModelData->{'Website URL'}.'/', '', $url);


        $title=$this->auroraModelData->{'Webpage Name'};
        if(!$title) {
            $title=$this->auroraModelData->{'Webpage Code'};
        }


        $this->parsedData =
            [
                'title' => $title,
                'url'   => $url
            ];
    }


    protected function fetchData($id): object|null
    {
        return DB::connection('aurora')
            ->table('Page Store Dimension')
            ->leftJoin('Website Dimension', 'Webpage Website Key', '=', 'Website Key')
            ->where('Page Key', $id)->first();
    }
}
