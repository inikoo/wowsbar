<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Market\Shop\Hydrators\ShopHydrateMailshots;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateMailshots;
use App\Enums\Mail\MailshotTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreMailshot
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer|Shop $parent;
    private string $scope;


    public function handle(Customer|Shop $parent, array $modelData): Mailshot
    {
        $this->parent = $parent;


        data_set($modelData, 'date', now());
        data_set(
            $modelData,
            'layout',
            json_decode(file_get_contents(resource_path('views/mailshots/layouts/default.json')), true)
        );

        /** @var Mailshot $mailshot */
        $mailshot = $parent->mailshots()->create($modelData);

        OrganisationHydrateMailshots::dispatch();
        if($mailshot->type==MailshotTypeEnum::PROSPECT_MAILSHOT) {
            ShopHydrateMailshots::dispatch($mailshot->scope);
        }


        return $mailshot;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }


    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'type'    => ['required', new Enum(MailshotTypeEnum::class)]
        ];
    }





    public function shopProspects(Shop $shop, ActionRequest $request): Mailshot
    {
        $request->merge(
            [
                'type' => MailshotTypeEnum::PROSPECT_MAILSHOT->value
            ]
        );
        $request->validate();

        return $this->handle($shop, $request->validated());
    }


    public function action(Shop|Customer $parent, array $objectData): Mailshot
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);

        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }


    public function jsonResponse(Mailshot $mailshot): string
    {
        return route(
            'customer.mailshots.mailshots.workshop',
            [
                $mailshot->slug
            ]
        );
    }

    public function htmlResponse(Mailshot $mailshot): RedirectResponse
    {
        return match ($mailshot->type) {
            MailshotTypeEnum::PROSPECT_MAILSHOT => redirect()->route(
                'org.crm.shop.prospects.mailshots.workshop',
                [
                    $mailshot->scope->slug,
                    $mailshot->slug
                ]
            ),
            default => null
        };

    }
}
