<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use Illuminate\Support\Collection;

trait WithNormalise
{
    public function normalise(Collection $shares): array
    {
        $total = $shares->sum();
        if ($total==0) {
            return $shares->all();
        }

        $normalisedShares = $shares->mapWithKeys(function ($share, $key) use ($total) {
            return [$key => $share / $total];
        });

        return $normalisedShares->all();
    }
}
