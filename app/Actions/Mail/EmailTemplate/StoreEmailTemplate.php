<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailTemplate;

use App\Enums\Mail\EmailTemplate\EmailTemplateTypeEnum;
use App\Models\Mail\EmailTemplate;
use App\Models\Mail\Mailshot;
use App\Models\Mail\Outbox;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Illuminate\Http\RedirectResponse;
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
    private string $scope;

    public function handle(Organisation|Shop|Outbox $parent, array $modelData): EmailTemplate
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $parent->emailTemplates()->create($modelData);
        SetEmailTemplateScreenshot::dispatch($emailTemplate);



        return $emailTemplate;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        if ($this->scope == 'prospects') {
            return $request->user()->hasPermissionTo("crm.prospects.mailshots.edit");
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'compiled' => ['required', 'array'],
            'type'     => ['required'],
        ];
    }

    public function fromMailshot(Mailshot $mailshot, ActionRequest $request): EmailTemplate
    {
        $this->scope = 'prospects';

        $this->mailshot = $mailshot;
        $this->fillFromRequest($request)
            ->fill(['compiled' => $mailshot->layout])
            ->fill(['type' => EmailTemplateTypeEnum::MARKETING]);

        $validated = $this->validateAttributes();

        $emailTemplate = $this->handle($mailshot->outbox, $validated);

        $mailshot->update(['data' => ['email_template_id' => $emailTemplate->id]]);

        return $emailTemplate;
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
