<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreOrganisationUserApiTokenFromQRCode
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(OrganisationUser $organisationUser): string
    {
        return $organisationUser->createToken('api-token')->plainTextToken;
    }


    public function rules(): array
    {
        return [
            'organisation_user_id'        => ['required', 'exists:organisation_users,id'],
        ];
    }


    public function prepareForValidation(): void
    {

        if($this->has('code')) {

            $organisationUserId=Cache::get('profile-app-qr-code:'.$this->get('code'));
            if($organisationUserId) {
                $this->fill([
                    'organisation_user_id' => $organisationUserId
                ]);
                Cache::forget('profile-app-qr-code:'.$this->get('code'));
            }
        }

    }

    public function getValidationMessages(): array
    {
        return [
            'organisation_user_id.required' => __('Invalid QR Code'),
        ];
    }


    public function asController(ActionRequest $request): string
    {
        $this->fillFromRequest($request);

        $validatedData = $this->validateAttributes();

        $organisationUser = OrganisationUser::find($validatedData['organisation_user_id']);
        return $this->handle($organisationUser);
    }
}
