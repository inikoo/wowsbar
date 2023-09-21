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
use App\Models\Market\Shop;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class GetScheduleAppointment
{
    use AsAction;
    use WithAttributes;


    /**
     * @throws Throwable
     */
    public function handle(string $date): Collection
    {
        return Appointment::whereDate('schedule_at', $date)->get();
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
    public function asController(ActionRequest $request): Collection
    {
        return $this->handle($request->input('date'));
    }

    public string $commandSignature = 'shop:new-customer {shop} {email} {--N|contact_name=} {--C|company=} {--P|password=}';

    /**
     * @throws \Throwable
     */
    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }


        $this->setRawAttributes([
            'contact_name' => $command->option('contact_name'),
            'company_name' => $command->option('company'),
            'email'        => $command->argument('email'),
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $customer = $this->handle($shop, $validatedData);

        $command->info("Customer $customer->slug created successfully 🎉");

        return 0;
    }
}
