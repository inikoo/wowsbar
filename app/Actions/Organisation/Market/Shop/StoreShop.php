<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 26 Aug 2022 01:35:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

namespace App\Actions\Organisation\Market\Shop;

use App\Actions\Organisation\Accounting\PaymentAccount\StorePaymentAccount;
use App\Enums\Helpers\SerialReference\SerialReferenceModelEnum;
use App\Models\Organisation\Market\Shop;
use App\Models\Organisation\Organisation;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreShop
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Organisation $organisation, array $modelData = []): Shop
    {
        data_set($modelData, 'code', $organisation->code, overwrite: false);
        data_set($modelData, 'name', $organisation->name, overwrite: false);
        data_set($modelData, 'country_id', $organisation->country_id, overwrite: false);
        data_set($modelData, 'language_id', $organisation->language_id, overwrite: false);
        data_set($modelData, 'timezone_id', $organisation->timezone_id, overwrite: false);
        data_set($modelData, 'currency_id', $organisation->currency_id, overwrite: false);


        /** @var Shop $shop */
        $shop = $organisation->shop()->create($modelData);
        $shop->stats()->create();
        $shop->accountingStats()->create();
        $shop->crmStats()->create();

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::CUSTOMER,
            ]
        );

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::ORDER,
            ]
        );

        $shop->serialReferences()->create(
            [
                'model' => SerialReferenceModelEnum::INVOICE,
            ]
        );


        $paymentAccount       = StorePaymentAccount::run($organisation->accountsServiceProvider(), [
            'code' => 'accounts-'.$shop->slug,
            'name' => 'Accounts '.$shop->code,
            'data' => [
                'service-code' => 'accounts'
            ]
        ]);
        $paymentAccount->slug = 'accounts-'.$shop->slug;
        $paymentAccount->save();

        return AttachPaymentAccountToShop::run($shop, $paymentAccount);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.edit");
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if ($request->get('identity_document_number') and !$request->get('identity_document_type')) {
            $validator->errors()->add('contact_name', 'document type required');
        }
        if ($request->get('identity_document_type') and !$request->get('identity_document_number')) {
            $validator->errors()->add('contact_name', 'document number required');
        }
    }


}
