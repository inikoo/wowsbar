<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Actions\Traits\WithActionUpdate;
use App\Helpers\ImgProxy\Image;
use App\Models\Media\Media;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteLayout
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $modelData): Website
    {
        $website->update(
            [
                'layout' => $modelData
            ]
        );

        /*
         *
         *  $layoutData = (array) $request;
        $media      = Media::find(Arr::get($layoutData, 'favicon'));
        if($media) {
            $favicon    = (new Image())->make($media->getImgProxyFilename())->resize(0, 180);
            return array_merge($layoutData, ['faviconSrc' => GetPictureSources::run($favicon)]);
        }
        return  $layoutData;
         */


        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'layout'      => ['required', 'string'],
            'footer'      => ['required', 'array'],
            'header'      => ['required', 'array'],
            'content'     => ['required', 'array'],
            'favicon'     => ['required', 'integer'],
            'imageLayout' => ['sometimes', 'nullable', 'integer'],
            'colorLayout' => ['required', 'string'],

        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();
        return $this->handle($website, $request->validated());
    }


}
