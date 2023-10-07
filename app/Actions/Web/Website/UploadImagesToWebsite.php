<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 16:37:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Http\Resources\Gallery\ImageResource;
use App\Models\Web\Website;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToWebsite
{
    use AsAction;
    use WithAttributes;


    public function handle(Website $website, string $scope, array $imageFiles): Collection
    {

        $medias=[];

        foreach ($imageFiles as $imageFile) {
            $medias[] = AttachImageToWebsite::run(
                website: $website,
                collection: $scope,
                imagePath: $imageFile->getPathName(),
                originalFilename: $imageFile->getClientOriginalName(),
                extension: $imageFile->guessClientExtension()
            );
        }


        return collect($medias);

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


    public function header(Website $website, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($website, 'header', $request->validated('images'));
    }

    public function footer(Website $website, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($website, 'footer', $request->validated('images'));
    }

    public function favicon(Website $website, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($website, 'favicon', $request->validated('images'));
    }

    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }

}
