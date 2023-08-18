<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Profile;

use App\Actions\Tenant\Auth\User\UI\SetUserAvatarFromImage;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Organisation\OrganisationUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;

class UpdateProfile
{
    use WithActionUpdate;

    private bool $asAction = false;


    public function handle(OrganisationUser $organisationUser, array $modelData, ?UploadedFile $avatar): OrganisationUser
    {

        if ($avatar) {
            SetUserAvatarFromImage::run(
                organisationUser: $organisationUser,
                imagePath: $avatar->getPathName(),
                originalFilename: $avatar->getClientOriginalName(),
                extension: $avatar->getClientOriginalExtension()
            );
        }

        return $this->update($organisationUser, $modelData, ['profile', 'settings']);
    }


    public function rules(): array
    {
        return [
            'password'    => ['sometimes', 'required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            //'email'       => 'sometimes|required|email|unique:organisationUsers,email',
            'about'       => 'sometimes|nullable|string|max:255',
            'language_id' => ['sometimes', 'required', 'exists:central.languages,id'],
            'avatar'      => [
                'sometimes',
                'nullable',
                File::image()
                    ->max(12 * 1024)
            ],


        ];
    }



    public function asController(ActionRequest $request): OrganisationUser
    {
        $this->fillFromRequest($request);
        $validated = $this->validateAttributes();
        return $this->handle($request->user(), Arr::except($validated, 'avatar'), Arr::get($validated, 'avatar'));
    }



    public function htmlResponse(OrganisationUser $organisationUser): RedirectResponse
    {
        return Redirect::route('org.profile.show');
    }
}
