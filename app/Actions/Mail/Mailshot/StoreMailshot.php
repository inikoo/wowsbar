<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Enums\Mail\MailshotTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
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

        $layout = [

        ];
        //   list($layout, $slides) = ParseMailshotLayout::run($layout);

        //data_set($modelData, 'ulid', Str::ulid());
        data_set($modelData, 'date', now());


        /** @var Mailshot $mailshot */
        $mailshot = $parent->mailshots()->create($modelData);

        /*
        $snapshot   = StoreMailshotSnapshot::run(
            $mailshot,
            [
                'layout' => $layout
            ],
            $slides
        );

        $mailshot->update(
            [
                'unpublished_snapshot_id' => $snapshot->id,
                'compiled_layout'         => $snapshot->compiledLayout()
            ]
        );
        $mailshot->stats()->create();


  */


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


    public function inCustomer(ActionRequest $request): Mailshot
    {
        $this->scope    = 'customer';
        $this->customer = $request->get('customer');

        $parent = customer();
        $request->validate();

        $validatedData = $request->validated();

        if ($portfolioWebsiteId = Arr::get($validatedData, 'portfolio_website_id')) {
            $parent = PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
    }


    public function inShop(Shop $shop, ActionRequest $request): Mailshot
    {
        $request->validate();

        return $this->handle($shop, $request->validated());
    }

    public function shopProspects(Shop $shop, ActionRequest $request)
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
                'org.models.shop.prospect-mailshot.show',
                [
                    $mailshot->scope->slug
                ]
            ),
            default => null
        };

    }
}
