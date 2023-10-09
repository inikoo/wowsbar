<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment;

use App\Enums\CRM\Appointment\AppointmentEventEnum;
use App\Enums\CRM\Appointment\AppointmentTypeEnum;
use App\Models\CRM\Appointment;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreAppointment
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public Customer|Shop $parent;

    public function handle(Customer|Shop $parent, array $modelData): Model
    {
        $this->parent = $parent;

        if(class_basename($parent) == 'Shop') {
            data_set($modelData, 'customer_id', $modelData['customer_id']);
        }

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
            'customer_id'              => ['sometimes'],
            'name'                     => ['required', 'string'],
            'schedule_at'              => ['required', 'string'],
            'description'              => ['nullable', 'string', 'max:255'],
            'type'                     => ['required', Rule::in(AppointmentTypeEnum::values())],
            'event'                    => ['required', Rule::in(AppointmentEventEnum::values())],
            'event_address'            => ['required', 'string']
        ];
    }

    /**
     * @throws Throwable
     */
    public function asController(Customer $customer, ActionRequest $request): Model
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($customer, $request->validated());
    }

    public function inShop(Shop $shop, ActionRequest $request): Model
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($shop, $request->validated());
    }

    public string $commandSignature = 'appointment:book {customer} {schedule} {--t|type=} {--e|event=} {--a|event_address=}';

    /**
     * @throws \Throwable
     */
    public function asCommand(Command $command): int
    {
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->setRawAttributes([
            'schedule_at'          => $command->argument('schedule'),
            'type'                 => $command->option('type'),
            'event'                => $command->option('event'),
            'event_address'        => $command->option('event_address')
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->handle($customer, $validatedData);

        $command->info("Appointment created successfully 🎉");

        return 0;
    }
}
