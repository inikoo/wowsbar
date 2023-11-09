<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment;

use App\Actions\Auth\User\Login;
use App\Enums\CRM\Appointment\AppointmentEventEnum;
use App\Enums\CRM\Appointment\AppointmentTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreCustomerAppointment
{
    use AsAction;
    use WithAttributes;
    use AsCommand;

    private bool $asAction = false;

    public Customer|Shop $parent;

    public function handle(array $modelData): Model
    {
        Login::run([
            'email' => $modelData['email'],
            'password' => $modelData['password']
        ]);

        $parent = customer();

        data_set($modelData, 'customer_id', $parent->id);
        data_set($modelData, 'shop_id', $parent->shop_id);

        return $parent->appointment()->create($modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
    }

    public function htmlResponse(): RedirectResponse
    {
        return match (class_basename($this->parent)) {
            'Shop' => redirect()->route('org.crm.shop.appointments.index', [
                'shop' => $this->parent
            ]),
            default => back(),
        };
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes'],
            'name' => ['sometimes', 'string'],
            'schedule_at' => ['sometimes'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(AppointmentTypeEnum::values())],
            'event' => ['sometimes', Rule::in(AppointmentEventEnum::values())],
            'event_address' => ['sometimes', 'string']
        ];
    }

    /**
     * @throws Throwable
     */
    public function asController(ActionRequest $request): Model
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($request->validated());
    }
}
