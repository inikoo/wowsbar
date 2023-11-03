<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Mail\MailshotStateEnum;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\ActionRequest;

class SetMailshotAsScheduled
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Mailshot $mailshot, array $modelData): Mailshot
    {
        $updateData = array_merge([
            'state' => MailshotStateEnum::SCHEDULED
        ], $modelData);

        if ($mailshot->state == MailshotStateEnum::IN_PROCESS) {
            $updateData['ready_at'] = $modelData['scheduled_at'];
        }

        $mailshot->update($updateData);

        return $mailshot;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'publisher_id'   => ['sometimes','exists:organisation_user:id'],
            'scheduled_at'   => ['required', 'string'],
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'publisher_id'   => $request->user()->id,
            ]
        );
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): string
    {
        $request->validate();
        $this->handle($mailshot, $request->validated());

        return "🫡";
    }

    public function action(Mailshot $mailshot, $modelData): Mailshot
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($mailshot, $validatedData);
    }
}
