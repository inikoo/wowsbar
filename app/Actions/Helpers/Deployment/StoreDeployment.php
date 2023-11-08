<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 08 Oct 2023 21:33:09 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Deployment;

use App\Models\Helpers\Deployment;
use App\Models\Mail\EmailTemplate;
use App\Models\Portfolio\Banner;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreDeployment
{
    use AsAction;

    public function handle(Banner|Website|Webpage|EmailTemplate $model, array $modelData): Deployment
    {

        /** @var Deployment $deployment */
        $deployment=$model->deployments()->create($modelData);
        $deployment->generateSlug();
        $deployment->saveQuietly();


        return $deployment;
    }
}
