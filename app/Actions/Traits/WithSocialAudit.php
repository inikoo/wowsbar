<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 11:12:24 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\CRM\CustomerSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccount;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;

trait WithSocialAudit
{
    private function createAudit(CustomerSocialAccount|PortfolioSocialAccount $model): void
    {
        $model->auditEvent     = 'created';
        $model->isCustomEvent  = true;
        $model->auditCustomOld = [];
        $model->auditCustomNew = [
            'username' => $model->username,
            'platform' => $model->platform,
        ];
        if ($model->url) {
            $model->auditCustomNew['url'] = $model->url;
        }
        Event::dispatch(AuditCustom::class, [$model]);
    }
}
