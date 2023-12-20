<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailTemplate;

use App\Actions\Helpers\Snapshot\StoreEmailTemplateSnapshot;
use App\Models\Mail\EmailTemplate;
use App\Models\Mail\Mailshot;
use App\Models\Mail\Outbox;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreEmailTemplate
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Organisation|Shop $parent;
    private Mailshot $mailshot;

    private array $queryRules;

    public function handle(Organisation|Shop|Outbox $parent, array $modelData): EmailTemplate
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $parent->emailTemplates()->create($modelData);


        //StoreEmailTemplateSnapshot::run($emailTemplate, $modelData);

        return $emailTemplate;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        // find a better way to do this
        return true;//$request->user()->hasPermissionTo("crm.prospects.edit");
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'compiled' => ['required', 'string']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fill([
            'compiled' => $this->mailshot->layout
        ]);
    }

    public function fromMailshot(Mailshot $mailshot, ActionRequest $request): EmailTemplate
    {
        $this->mailshot = $mailshot;
        $this->fillFromRequest($request);
        $this->fill(['content', $mailshot->layout]);
        $validated=$this->validateAttributes();


        return  $this->handle($mailshot->outbox, $validated);
    }

    public function action(Organisation|Shop $parent, $modelData): EmailTemplate
    {
        return $this->handle($parent, $modelData);
    }

    public function jsonResponse(EmailTemplate $emailTemplate): EmailTemplate
    {
        return $emailTemplate;
    }

    public function htmlResponse(EmailTemplate $emailTemplate): RedirectResponse
    {
        return redirect()->route(
            'org.crm.shop.mailroom.templates.workshop',
            [
                $emailTemplate->parent->slug,
                $emailTemplate->slug
            ]
        );
    }
}
