<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Models\Portfolio\Banner;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class DeliverBanner
{
    use AsController;

    public function handle(string $ulid): array
    {
        /*
        $client = BuildElasticsearchClient::run();
        $params = [
            'index' => config('elasticsearch.index_prefix').'content_blocks',
            'id'    => 'banner_'.$ulid
        ];
        $response = $client->get($params)->asString();
        $bannerSource=Arr::get(json_decode($response, true), '_source');
                return $bannerSource;
*/

        $seconds=3600;

        return Cache::remember('deliver_banner_compiled_layout_'.$ulid, $seconds, function () use ($ulid) {
            $banner = Banner::where('ulid', $ulid)->firstOrFail();
            return $banner->compiled_layout;
        });

    }


    public function htmlResponse(array $compiledLayout): Response
    {
        return Inertia::render(
            'Banner',
            [
                'data' => $compiledLayout
            ]
        );
    }

}
