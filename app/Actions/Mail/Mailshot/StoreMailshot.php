<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateEstimatedEmails;
use App\Actions\Mail\Outbox\Hydrators\OutboxHydrateMailshots;
use App\Actions\Market\Shop\Hydrators\ShopHydrateMailshots;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateMailshots;
use App\Enums\Mail\MailshotTypeEnum;
use App\Enums\Mail\Outbox\OutboxTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Helpers\Query;
use App\Models\Mail\Mailshot;
use App\Models\Mail\Outbox;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Illuminate\Database\Query\Builder;

class StoreMailshot
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer|Shop $parent;
    private string $scope;
    private array $queryRules;


    public function handle(Customer|Shop $parent, array $modelData): Mailshot
    {


        $this->parent = $parent;

        data_set($modelData, 'recipients_recipe', Arr::only($modelData, ['query_id', 'query_arguments']));
        Arr::forget($modelData, ['query_id', 'query_arguments']);

        data_set($modelData, 'date', now());
        data_set(
            $modelData,
            'layout',
            json_decode(file_get_contents(resource_path('views/mailshots/layouts/default.json')), true)
        );

        /** @var Mailshot $mailshot */
        $mailshot = $parent->mailshots()->create($modelData);
        $mailshot->mailshotStats()->create();
        $mailshot->refresh();

        OrganisationHydrateMailshots::dispatch();
        if ($mailshot->type == MailshotTypeEnum::PROSPECT_MAILSHOT) {
            ShopHydrateMailshots::dispatch($mailshot->scope);
        }

        $query = Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));
        $mailshot->mailshotStats()->update(
            [
                'number_estimated_dispatched_emails' => $query->number_items,
            ]
        );
        MailshotHydrateEstimatedEmails::dispatch($mailshot);
        OutboxHydrateMailshots::dispatch($mailshot->outbox);

        return $mailshot;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }

    public function prepareForValidation(): void
    {
        if (!$this->get('query_id')) {
            //todo this is only for testing
            $this->fill(['query_id' => 2]);
        }
    }

    public function rules(): array
    {
        return [
            'outbox_id'  => ['required', 'exists:outboxes,id'],
            'subject'    => ['required', 'string', 'max:255'],
            'type'       => ['required', new Enum(MailshotTypeEnum::class)],
            'recipients' => ['required', 'array']
            /*
            'query_arguments' => ['sometimes', 'array'],
            'query_id'        => [
                'required',
                Rule::exists('queries', 'id')->where(function (Builder $query) {
                    return $query->where('model_type', $this->queryRules['model_type'])
                        ->where('scope_type', $this->queryRules['scope_type'])
                        ->where('scope_id', $this->queryRules['scope_id']);
                }),
            ]
            */
        ];
    }

    public function shopProspects(Shop $shop, ActionRequest $request): Mailshot
    {
        $this->queryRules = [
            'model_type' => 'Prospect',
            'scope_type' => 'Shop',
            'scope_id'   => $shop->id
        ];

        $this->fillFromRequest($request);

        $this->fill(
            [
                'type'      => MailshotTypeEnum::PROSPECT_MAILSHOT->value,
                'outbox_id' => Outbox::where('shop_id', $shop->id)->where('type', OutboxTypeEnum::SHOP_PROSPECT)->pluck('id')->first()
            ]
        );


        $validatedData = $this->validateAttributes();


        return $this->handle($shop, $validatedData);
    }


    public function action(Shop|Customer $parent, array $objectData): Mailshot
    {
        if (Arr::get($objectData, 'type') == MailshotTypeEnum::PROSPECT_MAILSHOT) {
            $this->queryRules = [
                'model_type' => 'Prospect',
                'scope_type' => class_basename($parent),
                'scope_id'   => $parent->id
            ];
        }


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
