<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 09 Oct 2023 13:58:34 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Http\Resources\Gallery\ImageResource;
use App\Models\Web\Webpage;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

use function Sentry\captureException;

class UploadImagesToWebpage
{
    use AsAction;
    use WithAttributes;


    public function handle(Webpage $webpage, string $scope, array $imageFiles): Collection
    {

        $medias=[];

        foreach ($imageFiles as $imageFile) {
            $medias[] = AttachImageToWebpage::run(
                webpage: $webpage,
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
            'images.*' => ["mimes:jpg,png,jpeg|max:102400"]
        ];
    }


    public function asController(Webpage $webpage, ActionRequest $request): Collection
    {
        try {
            $request->validate();
        } catch (Exception $e) {
            captureException($e);
        }
        return $this->handle($webpage, 'header', $request->validated('images'));
    }



    public function jsonResponse($medias): AnonymousResourceCollection
    {
        return ImageResource::collection($medias);
    }

}
