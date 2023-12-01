<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 17:29:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $tag_slug
 * @property string $question
 * @property integer $number_prospects
 */
class SurveysResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'label'            => $this->question,
        ];
    }
}
