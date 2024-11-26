<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 15 Oct 2023 09:10:43 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Portfolio;

use App\Http\Resources\HasSelfCall;
use App\Models\Announcement;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @property mixed $websites
 */
class AnnouncementsResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Announcement $announcement */
        $announcement = $this;

        $extractedSettings = $announcement->extractSettings($announcement->settings);

        return [
            'ulid'                     => $announcement->ulid,
            'code'                     => $announcement->code,
            'name'                     => $announcement->name,
            'show_pages' => $extractedSettings['show_pages'],
            'hide_pages' => $extractedSettings['hide_pages'],
        ];
    }
}
