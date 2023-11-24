<?php

namespace App\Actions\Mail\Mailshot;

use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateEstimatedEmails;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class EstimateRecipientsCreatingMailshot
{
    use AsAction;
    use WithAttributes;

    public function handle($recipientsData): int
    {
        return 100;
        dd($recipientsData);

        return MailshotHydrateEstimatedEmails::make()->getNumberEstimatedRecipients($mailshot->recipients_recipe);
    }

    public function rules(): array
    {
        return [
            'recipients' => ['required', 'array']
        ];
    }

    public function asController(Shop $shop, ActionRequest $request): int
    {
        $this->fillFromRequest($request);

        return $this->handle($this->validateAttributes());
    }
}
