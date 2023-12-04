<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 12 Jun 2023 13:57:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 *
 * @property string $slug
 * @property string $name
 * @property string $model_type
 * @property mixed $constrains
 * @property mixed $arguments
 * @property boolean $is_seeded
 *
 */
class UploadsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Helpers\Upload $upload */
        $upload = $this;

        return [
            'type'           => $upload->type,
            'uploaded_at'    => $upload->uploaded_at,
            'filename'       => $upload->filename,
            'number_rows'    => $upload->number_rows,
            'number_success' => $upload->number_success,
            'number_fails'   => $upload->number_fails,
            'path'           => $upload->path,
            'download_route' => [
                'name' => 'org.crm.uploads.download',
                'parameters' => $upload->id,
            ],
        ];
    }
}
