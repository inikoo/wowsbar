<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\ShipperAccount;

use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Shipper;
use App\Models\ShipperAccount;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreShipperAccount
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    /**
     * @throws Throwable
     */
    public function handle(Customer $customer, array $modelData): ShipperAccount
    {
        data_set($modelData, 'data', []);

        /** @var ShipperAccount */
        return $customer->shipperAccounts()->create($modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo('crm.edit');
    }

    public function rules(): array
    {
        return [
            'label'        => ['required', 'string', 'max:255'],
            'shipper_id'   => ['required', 'exists:shippers,id'],
            'credentials'  => ['required', 'string', 'max:255']
        ];
    }
    /**
     * @throws Throwable
     */
    public function inShopInCustomer(Shop $shop, Customer $customer, ActionRequest $request): ShipperAccount
    {
        $this->fillFromRequest($request);

        return $this->handle($customer, $this->validateAttributes());
    }

    public function htmlResponse(ShipperAccount $shipperAccount): RedirectResponse
    {
        return Redirect::route('org.crm.shop.customers.show', [
            $shipperAccount->customer->shop->slug,
            $shipperAccount->customer->slug,
            'tab' => 'shipper_accounts'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function action(Customer $customer, $objectData): ShipperAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($customer, $validatedData);
    }

    public string $commandSignature = 'customer:shipper-account {customer} {shipper} {--C|credentials=}';

    /**
     * @throws \Throwable
     */
    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
            $provider = Shipper::where('slug', $command->argument('shipper'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $attributes = [
            'provider_id' => $provider->id,
            'credentials' => $command->option('credentials')
        ];

        $this->setRawAttributes($attributes);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $shipperAccount = $this->handle($customer, $validatedData);

        $command->info("Shipper Account $shipperAccount->slug created successfully 🎉");

        return 0;
    }
}
