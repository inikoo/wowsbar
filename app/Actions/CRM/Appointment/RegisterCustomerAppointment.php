<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment;

use App\Actions\Auth\User\Login;
use App\Actions\CRM\Customer\Register;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class RegisterCustomerAppointment
{
    use AsAction;
    use WithAttributes;
    use AsCommand;

    private bool $asAction = false;

    public Customer|Shop $parent;

    public function handle(array $modelData): Authenticatable
    {
        Register::run([
            'email'    => $modelData['email'],
            'password' => $modelData['password']
        ]);

        return Auth::guard('customer')->user();
    }

    /**
     * @throws Throwable
     */
    public function asController(ActionRequest $request): Authenticatable
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($request->validated());
    }
}
