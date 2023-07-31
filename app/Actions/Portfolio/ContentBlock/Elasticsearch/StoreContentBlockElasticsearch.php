<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:48:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Elasticsearch;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Models\Portfolio\ContentBlock;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreContentBlockElasticsearch
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(ContentBlock $contentBlock): Elasticsearch
    {
        $client = BuildElasticsearchClient::run();

        $params = [
            'id'  => $contentBlock->id,
            'index'  => strtolower($contentBlock->ulid),
            'body'   => json_encode([
                'name'   => $contentBlock->name,
                'slug'   => $contentBlock->slug,
                'layout' => $contentBlock->layout,
                'data'   => $contentBlock->data
            ])
        ];

        return $client->index($params);
    }
}
