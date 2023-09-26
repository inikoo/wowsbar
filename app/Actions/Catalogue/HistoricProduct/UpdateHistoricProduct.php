<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:53:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\HistoricProduct;

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
