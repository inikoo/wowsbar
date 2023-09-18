<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class DeliverBanner
{
    use AsController;

    public function handle(string $ulid): array
    {

        $client = BuildElasticsearchClient::run();

        $params = [
            'index' => config('elasticsearch.index_prefix') . config('app.env').'_content_blocks',
            'id'    => 'banner_'.$ulid
        ];

        $response = $client->get($params)->asString();

        return Arr::get(json_decode($response, true), '_source');

    }


    public function htmlResponse(array $compiledLayout): Response
    {
        return Inertia::render(
            'Delivery/Banner',
            [
                'data' => $compiledLayout
            ]
        );
    }

}
