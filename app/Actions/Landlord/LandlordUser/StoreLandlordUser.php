<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Landlord\LandlordUser;


use App\Models\Landlord\LandlordUser;
use App\Rules\AlphaDashDot;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreLandlordUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(array $objectData = []): LandlordUser
    {
        /** @var LandlordUser $landlordUser */
        $landlordUser = LandlordUser::create($objectData);
        $landlordUser->stats()->create();
        //SetUserAvatar::run($landlordUser);

       // UserHydrateUniversalSearch::dispatch($landlordUser);
       // TenantHydrateUsers::dispatch(app('currentTenant'));
        return $landlordUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'username' => ['required', new AlphaDashDot(), 'unique:landlord_users,username', Rule::notIn(['export', 'create'])],
            'password' => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'    => ['sometimes','required', 'email', 'unique:landlord_users,email']
        ];
    }

    public function action(array $objectData = []): LandlordUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();
        return $this->handle($validatedData);
    }


}
