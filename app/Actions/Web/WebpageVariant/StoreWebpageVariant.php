<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebpageVariant;

use App\Models\Web\Webpage;
use App\Models\Web\WebpageVariant;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebpageVariant
{
    use AsAction;

    public function handle(Webpage $webpage, array $modelData): WebpageVariant
    {
        $modelData['code'] = $webpage->slug;

        /** @var WebpageVariant $webpageVariant */
        $webpageVariant = $webpage->variants()->create($modelData);
        $webpageVariant->stats()->create();

        if(!$webpage->main_variant_id) {
            $webpage->update(
                [
                    'main_variant_id' => $webpageVariant->id
                ]
            );
        }

        // todo
        //StoreWebVariantElasticsearch::run($webpageVariant);

        return $webpageVariant;
    }
}
