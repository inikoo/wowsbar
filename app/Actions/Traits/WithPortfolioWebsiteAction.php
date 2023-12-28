<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 11:30:11 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\CRM\CustomerWebsite;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;

trait WithPortfolioWebsiteAction
{
    private function createAudit(CustomerWebsite|PortfolioWebsite $model): void
    {
        $model->auditEvent     = 'created';
        $model->isCustomEvent  = true;
        $model->auditCustomOld = [];
        $model->auditCustomNew = [
            'url'  => $model->url,
            'name' => $model->name
        ];
        Event::dispatch(AuditCustom::class, [$model]);
    }



}
