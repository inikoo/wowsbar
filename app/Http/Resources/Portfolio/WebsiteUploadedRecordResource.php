<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @property numeric $number_banners
 */
class WebsiteUploadedRecordResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Helpers\UploadRecord $websitePortfolio */
        $websitePortfolio = $this;

        return [
            'code'           => Arr::get($websitePortfolio->data, 'code'),
            'name'           => Arr::get($websitePortfolio->data, 'name'),
            'domain'         => Arr::get($websitePortfolio->data, 'domain')
        ];
    }
}
