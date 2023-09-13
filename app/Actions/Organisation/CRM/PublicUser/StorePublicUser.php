<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\PublicUser;

use App\Models\CRM\Customer;
use App\Models\CRM\PublicUser;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePublicUser
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(Customer $customer, array $objectData = []): PublicUser
    {
        /** @var PublicUser $publicUser */
        $publicUser = $customer->publicUsers()->create($objectData);
        // $publicUser->stats()->create();

        return $publicUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("org.crm.edit");
    }

    public function rules(): array
    {
        return [
            'contact_name'     => 'sometimes|required|string|max:255',
            'password'         => ['required', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'email'            => ['required', 'email', 'unique:public_users,email']
        ];
    }

    public function action(Customer $customer, ?array $objectData = []): PublicUser
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:add-user {customer} {--e|email=} {--P|password=}';
    }

    public function asCommand(Command $command): int
    {
        $this->asAction = true;
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');
            return 1;
        }
        $this->setRawAttributes(
            [
                'email'    => $command->option('email')    ?? $customer->email,
                'password' => $command->option('password') ?? (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
            ]
        );
        $validatedData = $this->validateAttributes();
        $publicUser    = $this->handle($customer, $validatedData);

        $command->line("Public user $publicUser->email created successfully");

        return 0;
    }

}
