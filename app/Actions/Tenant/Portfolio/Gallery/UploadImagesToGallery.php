<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:58 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Gallery;

use App\Actions\Tenant\Auth\User\UI\AttachImageToTenant;
use App\Http\Resources\Gallery\ImageResource;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToGallery
{
    use AsAction;
    use WithAttributes;

    private PortfolioWebsite|null $website = null;

    public function handle(array $imageFiles): Collection
    {

        $medias=[];

        foreach ($imageFiles as $imageFile) {
            $medias[] = AttachImageToTenant::run(
                tenant: customer(),
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

        return $request->user()->can("portfolio.gallery.edit");
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

    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }
}
