<?php

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 20 Jun 2024 11:51:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web;

use App\Actions\Helpers\Media\StoreMediaFromFile;
use App\Actions\Traits\WithAttachMediaToModel;
use App\Http\Resources\Gallery\ImageResource;
use App\Models\Announcement;
use App\Models\Web\WebBlock;
use App\Models\Web\Website;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

trait WithUploadWebImage
{
    use WithAttachMediaToModel;

    public function handle(WebBlock|Website|Announcement $model, string $scope, array $modelData): Collection
    {
        $medias = [];

        foreach ($modelData['images'] as $imageFile) {
            $imageData = [
                'path'         => $imageFile->getPathName(),
                'originalName' => $imageFile->getClientOriginalName(),
                'extension'    => $imageFile->guessClientExtension(),
                'checksum'     => md5_file($imageFile->getPathName())
            ];

            $media = StoreMediaFromFile::run($model, $imageData, $scope);
            $this->attachMediaToModel($model, $media, $scope);
            $medias[] = $media;
        }

        return collect($medias);
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => ["mimes:jpg,png,jpeg,gif", "max:50000"]
        ];
    }

    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }
}
