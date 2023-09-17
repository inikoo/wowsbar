<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 17:23:28 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Elasticsearch;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Models\Web\WebpageVariant;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteWebVariantElasticsearch
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(WebpageVariant $webpageVariant): Elasticsearch
    {
        $client = BuildElasticsearchClient::run();

        $params = [
            'index'  => strtolower($webpageVariant->slug),
            'body'   => json_encode($webpageVariant->components)
        ];

        return $client->deleteByQuery($params);
    }
}
