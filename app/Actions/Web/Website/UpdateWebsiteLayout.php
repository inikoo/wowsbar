<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\File;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteLayout
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $modelData): Website
    {

        if(Arr::exists($modelData, 'logo')) {
            /** @var UploadedFile $logo */
            $logo=Arr::get($modelData, 'logo');
            Arr::forget($modelData, 'logo');

            UploadWebsiteLogo::run(
                website: $website,
                imagePath: $logo->getPathName(),
                originalFilename: $logo->getClientOriginalName(),
                extension: $logo->getClientOriginalExtension()
            );

        }




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
            'logo'      => [
                'sometimes',
                'nullable',
                File::image()
                    ->max(12 * 1024)
            ],

        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();
        return $this->handle($website, $request->validated());
    }


}
