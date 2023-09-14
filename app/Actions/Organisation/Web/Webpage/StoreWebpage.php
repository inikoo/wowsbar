<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 17:23:28 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage;

use App\Actions\Organisation\Web\WebpageVariant\StoreWebpageVariant;
use App\Models\Organisation\Web\Webpage;
use App\Models\Organisation\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebpage
{
    use AsAction;

    public function handle(Website $website, array $modelData, array $webpageVariantData = []): Webpage
    {
        data_set($modelData, 'level', $this->getLevel(Arr::get($modelData, 'parent_id')));


        /** @var Webpage $webpage */
        $webpage = $website->webpages()->create($modelData);
        $webpage->stats()->create();

        StoreWebpageVariant::run($webpage, $webpageVariantData);

        return $webpage;
    }

    public function getLevel($parent_id): int
    {
        /** @var Webpage $parent */
        if ($parent = Webpage::find($parent_id)) {
            return $parent->level + 1;
        }


        return 1;
    }


}
