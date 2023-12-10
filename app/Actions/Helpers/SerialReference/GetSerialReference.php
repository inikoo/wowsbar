<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:18:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\SerialReference;

use App\Models\Helpers\SerialReference;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSerialReference
{
    use AsAction;

    /**
     * @throws \Throwable
     */
    public function handle(Organisation|Shop $container, $modelType): string
    {
        $serialReference = $this->getSerialReference($container, $modelType);

        $serial=DB::transaction(function () use ($serialReference) {
            $res = DB::table('serial_references')->select('serial')
                ->where('id', $serialReference->id)->first();

            $serial = (int) $res->serial + 1;


            DB::table('serial_references')
                ->where('id', $serialReference->id)
                ->update(['serial' => $serial]);
            return $serial;
        });

        return sprintf($serialReference->format, $serial);
    }


    private function getSerialReference($container, $modelType): SerialReference
    {
        return match (class_basename($container)) {
            'Organisation', 'Shop' => $container->serialReferences()->where('model', $modelType)->firstOrFail()
        };
    }

}
