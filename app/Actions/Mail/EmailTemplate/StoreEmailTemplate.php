<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:58:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailTemplate;

use App\Actions\Helpers\Snapshot\StoreEmailTemplateSnapshot;
use App\Models\Mail\EmailTemplate;
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
    private string $scope;
    private array $queryRules;

    public function handle(Organisation|Shop $parent, array $modelData): EmailTemplate
    {
        $emailTemplate = $parent->emailTemplates()->create($modelData);

        StoreEmailTemplateSnapshot::run($emailTemplate, $modelData);

        return $emailTemplate;
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
            'title'    => ['required', 'string', 'max:255'],
            'data'     => ['required', 'string'],
            'compiled' => ['required', 'string']
        ];
    }

    public function jsonResponse(EmailTemplate $emailTemplate): string
    {
        return route(
            'org.crm.shop.mailroom.templates.workshop',
            [
                $emailTemplate->scope->slug,
                $emailTemplate->slug
            ]
        );
    }

    public function htmlResponse(EmailTemplate $emailTemplate): RedirectResponse
    {
        return redirect()->route(
            'org.crm.shop.mailroom.templates.workshop',
            [
                $emailTemplate->scope->slug,
                $emailTemplate->slug
            ]
        );
    }
}
