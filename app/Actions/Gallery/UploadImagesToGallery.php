<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 13:04:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Actions\Portfolio\ContentBlock\AttachImageToContentBlock;
use App\Http\Resources\Media\MediaResource;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToGallery
{
    use AsAction;
    use WithAttributes;

    private Website|null $website = null;

    public function handle(array $imageFiles): Collection
    {

        $medias=[];

        foreach ($imageFiles as $imageFile) {
            $medias[] = AttachImageToTenant::run(
                tenant: app('currentTenant'),
                collection: 'content_block',
                imagePath: $imageFile->getPathName(),
                originalFilename: $imageFile->getClientOriginalName(),
                extension: $imageFile->guessClientExtension()
            );
        }

        return collect($medias);
    }

    public function authorize(ActionRequest $request): bool
    {

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => ["mimes:jpg,png,jpeg|max:20000"]
        ];
    }

    public function asController(ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($request->validated('images'));
    }

    public function htmlResponse($medias): AnonymousResourceCollection
    {
        return MediaResource::collection($medias);
    }
}
