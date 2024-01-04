<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\ShipperAccount;

use App\Actions\Traits\WithActionUpdate;
use App\Models\ShipperAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class UpdateShipperAccount
{
    use AsAction;
    use WithActionUpdate;
    use WithAttributes;

    private bool $asAction = false;


    /**
     * @throws Throwable
     */
    public function handle(ShipperAccount $shipperAccount, array $modelData): ShipperAccount
    {
        data_set($modelData, 'data', []);

        /** @var ShipperAccount */
        return $this->update($shipperAccount, $modelData);
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
            'label'        => ['sometimes', 'string', 'max:255'],
            'shipper_id'   => ['sometimes', 'exists:shippers,id'],
            'credentials'  => ['sometimes', 'string', 'max:255']
        ];
    }
    /**
     * @throws Throwable
     */
    public function asController(ShipperAccount $shipperAccount, ActionRequest $request): ShipperAccount
    {
        $this->fillFromRequest($request);

        return $this->handle($shipperAccount, $this->validateAttributes());
    }

    public function htmlResponse(ShipperAccount $shipperAccount): RedirectResponse
    {
        return Redirect::route('org.crm.shop.customers.shipper-accounts.show', [
            $shipperAccount->customer->shop->slug,
            $shipperAccount->customer->slug,
            $shipperAccount->slug
        ]);
    }

    /**
     * @throws Throwable
     */
    public function action(ShipperAccount $shipperAccount, $objectData): ShipperAccount
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shipperAccount, $validatedData);
    }
}
