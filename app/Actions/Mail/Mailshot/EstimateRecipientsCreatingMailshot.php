<?php

namespace App\Actions\Mail\Mailshot;

use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class EstimateRecipientsCreatingMailshot
{
    use AsAction;
    use WithAttributes;
    use WithRecipientsInput;

    private bool $asAction = false;

    public function handle(Shop $parent, $recipientsData): int
    {
        return GetEstimatedNumberRecipients::run($parent, $recipientsData);
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
            'recipients_recipe' => ['required', 'array']
        ];
    }


    /**
     * @throws \Exception
     */
    public function asController(Shop $shop, ActionRequest $request): int
    {
        $this->fillFromRequest($request);

        return $this->handle(
            $shop,
            $this->postProcessRecipients(Arr::get($this->validateAttributes(), 'recipients_recipe'))
        );
    }
}
