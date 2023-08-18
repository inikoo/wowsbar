<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:58 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Gallery;

use App\Actions\Tenant\Auth\User\UI\AttachImageToTenant;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Media\Media;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;

class UpdateUploadedImage
{
    use WithActionUpdate;

    private PortfolioWebsite|null $website = null;

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
