<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\CRM\Customer\AttachImageToCustomer;
use App\Http\Resources\Gallery\ImageResource;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

use function Sentry\captureException;

class UploadImagesToPortfolioWebsite
{
    use AsAction;
    use WithAttributes;


    private PortfolioWebsite|null $portfolioWebsite = null;


    public function handle(PortfolioWebsite $portfolioWebsite, array $imageFiles): Collection
    {
        $medias = [];
        foreach ($imageFiles as $imageFile) {
            $media = AttachImageToCustomer::run(
                customer: customer(),
                collection: 'portfolio_websites',
                imagePath: $imageFile->getPathName(),
                originalFilename: $imageFile->getClientOriginalName(),
                extension: $imageFile->guessClientExtension()
            );


            $medias[] = $media;
            $scope    = 'unpublished-slide';
            $count    = $portfolioWebsite->images()->wherePivot('scope', $scope)->count();

            if ($count == 0) {
                $portfolioWebsite->images()->attach(
                    $media->id,
                    [
                        'scope' => $scope
                    ]
                );
            }
        }

        return collect($medias);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => ["mimes:jpg,png,jpeg,gif,mp4","max:50000"]
        ];
    }


    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Collection
    {
        try {
            $request->validate();
        } catch (Exception $e) {
            captureException($e);
        }
        return $this->handle($portfolioWebsite, $request->validated('images'));
    }



    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }

}
