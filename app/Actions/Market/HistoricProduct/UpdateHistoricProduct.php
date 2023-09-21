<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\HistoricProduct;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Market\HistoricProduct;

class UpdateHistoricProduct
{
    use WithActionUpdate;

    public function handle(HistoricProduct $historicProduct, array $modelData): HistoricProduct
    {
        return $this->update($historicProduct, $modelData);
    }
}
