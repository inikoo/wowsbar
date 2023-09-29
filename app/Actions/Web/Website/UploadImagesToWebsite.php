<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 16:37:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Http\Resources\Gallery\ImageResource;
use App\Models\Media\Media;
use App\Models\Web\Website;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToWebsite
{
    use AsAction;
    use WithAttributes;


    public function handle(Website $website, UploadedFile $imageFile): Media
    {
        $media = AttachImageToWebsite::run(
            website: $website,
            collection: 'structure',
            imagePath: $imageFile->getPathName(),
            originalFilename: $imageFile->getClientOriginalName(),
            extension: $imageFile->guessClientExtension()
        );


        $scope        = 'tmp';
        $existing_ids = $website->images()->where('scope', $scope)->whereIn('media_id', [$media->id])->pluck('media_id');
        $website->images()->attach(
            collect([$media->id])->diff($existing_ids),
            [
                'scope' => 'tmp'
            ]
        );


        return $media;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => ["mimes:jpg,png,jpeg|max:20000"]
        ];
    }


    public function asController(Website $website, ActionRequest $request): Media
    {
        $request->validate();

        return $this->handle($website, $request->validated('images'));
    }

    public function jsonResponse(Media $media): array
    {
        return ImageResource::make($media)->getArray();
    }

}
