<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\CRM\Customer\AttachImageToCustomer;
use App\Http\Resources\Gallery\ImageResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToBanner
{
    use AsAction;
    use WithAttributes;


    private PortfolioWebsite|null $portfolioWebsite = null;


    public function handle(Banner $banner, array $imageFiles): Collection
    {

        $medias=[];
        foreach ($imageFiles as $imageFile) {

            $media =AttachImageToCustomer::run(
                customer: customer(),
                collection: 'content_block',
                imagePath: $imageFile->getPathName(),
                originalFilename: $imageFile->getClientOriginalName(),
                extension: $imageFile->guessClientExtension()
            );

            $medias[]=$media;

            $banner->images()->attach(
                $media->id,
                [
                    'scope'=> 'tmp'
                ]
            );

        }

        return collect($medias);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => ["mimes:jpg,png,jpeg|max:20000"]
        ];
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inBannerInPortfolioWebsite(PortfolioWebsite $portfolioWebsite, Banner $banner, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($banner, $request->validated('images'));
    }

    public function inBanner(Banner $banner, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($banner, $request->validated('images'));
    }

    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }

}
