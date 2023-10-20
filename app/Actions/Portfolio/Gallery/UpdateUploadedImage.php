<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery;

use App\Actions\CRM\Customer\AttachImageToCustomer;
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
                AttachImageToCustomer::run(
                    customer: customer(),
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
        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string'],
            'images'   => ['sometimes'],
            'images.*' => ["mimes:jpg,png,jpeg|max:102400"]
        ];
    }

    public function asController(Media $media, ActionRequest $request): Media
    {
        $request->validate();
        return $this->handle($media, $request->validated());
    }
}
