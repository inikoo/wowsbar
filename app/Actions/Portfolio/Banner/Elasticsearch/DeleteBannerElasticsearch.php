<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\Elasticsearch;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Models\Portfolio\Banner;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteBannerElasticsearch
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;

    public function handle(Banner $banner): void
    {
        $client = BuildElasticsearchClient::run();

        $params = [
            'id'    => 'banner_'.$banner->ulid,
            'index' => config('elasticsearch.index_prefix').'content_blocks',
        ];

        try {
            $client->delete($params);
        } catch (Exception) {
        }
    }
}
