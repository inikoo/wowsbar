<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 13:04:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Http\Resources\Gallery\ImageResource;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadImagesToContentBlock
{
    use AsAction;
    use WithAttributes;


    private Website|null $website = null;


    public function handle(ContentBlock $contentBlock, array $imageFiles): Collection
    {

        $medias=[];

        foreach ($imageFiles as $imageFile) {
            $medias[]=AttachImageToContentBlock::run(
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

    public function inBannerInWebsite(Website $website, ContentBlock $banner, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($banner, $request->validated('images'));
    }

    public function inBanner(ContentBlock $banner, ActionRequest $request): Collection
    {
        $request->validate();
        return $this->handle($banner, $request->validated('images'));
    }



    public function htmlResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }
}
