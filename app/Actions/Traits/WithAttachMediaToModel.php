<?php

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 30 May 2024 08:37:52 Central European Summer Time, Mijas Costa, Spain
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Announcement;
use App\Models\Catalogue\Product;
use App\Models\Media\Media;
use App\Models\SysAdmin\Organisation;
use App\Models\Web\WebBlock;
use App\Models\Web\Website;
use stdClass;

trait WithAttachMediaToModel
{
    protected function attachMediaToModel(Organisation|Website|WebBlock|Product|Announcement $model, Media $media, string $scope = 'default', string $subScope = null, $data = null): Organisation|Website|WebBlock|Product|Announcement
    {
        $group_id = $model->group_id;

        if ($model instanceof Organisation) {
            $organisation_id = $model->id;
        } else {
            $organisation_id = $model->organisation_id;
        }

        if (!$data) {
            $data = new stdClass();
        }

        $model->images()->attach(
            [
                $media->id => [
                    'group_id'        => $group_id,
                    'organisation_id' => $organisation_id,
                    'scope'           => $scope,
                    'sub_scope'       => $subScope,
                    'data'            => json_encode($data)
                ]
            ]
        );

        return $model;
    }

}
