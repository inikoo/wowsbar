<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 17 Oct 2023 18:23:18 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Models\Media\Media;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property Media $screenshot
 * @property string $code
 */
class AnnouncementTemplatesResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'code'         => $this->code,
            'category'         => $this->category,
            'source'       => GetPictureSources::run($this->screenshot->getImage()->resize(120, 72)),
        ];
    }
}
