<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 15:03:58 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Profile;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

class GetProfileAppLoginQRCode
{
    use WithActionUpdate;

    private bool $asAction = false;


    public function handle(OrganisationUser $organisationUser): array
    {

        $code=$this->getCode();
        Cache::put('profile-app-qr-code:'.$code, $organisationUser->id, 120);
        return [
            'code' => $code
        ];
    }

    public function getCode(): string
    {
        return Str::ulid();
    }

    public function asController(ActionRequest $request): array
    {
        $this->validateAttributes();

        return $this->handle($request->user());
    }


}
