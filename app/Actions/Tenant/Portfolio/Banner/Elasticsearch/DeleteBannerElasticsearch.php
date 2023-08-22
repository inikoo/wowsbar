<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\Elasticsearch;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Models\Portfolio\Banner;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteBannerElasticsearch
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(Banner $contentBlock): Elasticsearch
    {
        $client = BuildElasticsearchClient::run();

        $params = [
            'id'     => $contentBlock->id,
            'index'  => config('elasticsearch.index_prefix') . '_'.config('app.env').'_content_blocks',
        ];

        return $client->delete($params);
    }
}
