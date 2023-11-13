<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment;

use App\Actions\Auth\User\Login;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class LoginCustomerAppointment
{
    use AsAction;
    use WithAttributes;
    use AsCommand;

    public function handle(ActionRequest $request): Authenticatable
    {
        Login::run($request);

        return Auth::guard('customer')->user();
    }

    /**
     * @throws Throwable
     */
    public function asController(ActionRequest $request): Authenticatable
    {
        return $this->handle($request);
    }
}
