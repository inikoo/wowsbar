<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Models\Portfolio\PortfolioSocialAccount;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class PortfolioSocialAccountPostsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Portfolio\PortfolioSocialAccountPost $post */
        $post = $this;

        return [
            'task_name' => $post->task_name,
            'start_at' => $post->start_at,
            'end_at' => $post->end_at,
            'duration' => $post->duration,
            'type' => $post->type,
            'status' => $post->status,
            'description' => $post->description,
            'notes' => $post->notes,
            'platform' => $post->platform->platform->platformIcon()[$post->platform->platform->value]
        ];
    }
}
