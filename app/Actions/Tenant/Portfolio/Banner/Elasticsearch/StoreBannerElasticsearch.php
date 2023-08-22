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

class StoreBannerElasticsearch
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(Banner $banner): Elasticsearch
    {
        $client = BuildElasticsearchClient::run();

        $params = [
            'id'    => 'banner_'.$banner->ulid,
            'index' => config('elasticsearch.index_prefix') . config('app.env').'_content_blocks',
            'body'  => json_encode([
                'id'     => $banner->id,
                'type'   => 'banner',
                'name'   => $banner->name,
                'slug'   => $banner->slug,
                'layout' => $banner->layout,
                'data'   => $banner->data
            ])
        ];

        return $client->index($params);
    }
}
