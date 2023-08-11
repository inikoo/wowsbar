<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 13:04:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Gallery\ImageResource;
use App\Models\Media\Media;
use App\Models\Portfolio\Website;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateUploadedImage
{
    use WithActionUpdate;

    private Website|null $website = null;

    public function handle(Media $media, ?array $modelData): Media
    {
        $imageFiles = $modelData['images'] ?? [];

        if(count($imageFiles) > 0) {
            foreach ($imageFiles as $imageFile) {
                AttachImageToTenant::run(
                    tenant: app('currentTenant'),
                    collection: 'content_block',
                    imagePath: $imageFile->getPathName(),
                    originalFilename: $imageFile->getClientOriginalName(),
                    extension: $imageFile->guessClientExtension()
                );
            }
        }

        return $this->update($media, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string'],
            'images'   => ['sometimes'],
            'images.*' => ["mimes:jpg,png,jpeg|max:20000"]
        ];
    }

    public function asController(Media $media, ActionRequest $request): Media
    {
        $request->validate();
        return $this->handle($media, $request->validated());
    }
}
