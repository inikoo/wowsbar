<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 15:25:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation;

use App\Actions\Traits\WithActionUpdate;
use App\Models\SysAdmin\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\File;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\UploadedFile;

class UpdateOrganisation
{
    use WithActionUpdate;


    public function handle(Organisation $organisation, array $modelData): Organisation
    {
        if(Arr::exists($modelData, 'logo')) {
            /** @var UploadedFile $logo */
            $logo=Arr::get($modelData, 'logo');
            Arr::forget($modelData, 'logo');

            UploadOrganisationLogo::run(
                organisation: $organisation,
                imagePath: $logo->getPathName(),
                originalFilename: $logo->getClientOriginalName(),
                extension: $logo->getClientOriginalExtension()
            );

        }

        return $this->update($organisation, $modelData, ['data']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("sysadmin.edit");
    }


    public function rules(): array
    {
        return [
            'name'      => ['sometimes','required','max:64','iunique:organisations'],
            'logo'      => [
                'sometimes',
                'nullable',
                File::image()
                    ->max(12 * 1024)
            ],
        ];
    }

    public function asController(ActionRequest $request): Organisation
    {
        $this->fillFromRequest($request);
        return $this->handle(organisation(), $this->validateAttributes());
    }

    public function htmlResponse(): RedirectResponse
    {
        Session::put('reloadLayout', '1');
        return back();
    }

}
