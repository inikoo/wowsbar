<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

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


    public function handle(Banner $contentBlock, array $imageFiles): Collection
    {

        $medias=[];
        foreach ($imageFiles as $imageFile) {
            $medias[]=AttachImageToBanner::run(
                contentBlock:$contentBlock,
                file:$imageFile
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
