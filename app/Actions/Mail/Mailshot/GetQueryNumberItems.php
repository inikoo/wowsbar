<?php

namespace App\Actions\Mail\Mailshot;

use App\Models\Helpers\Query;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class GetQueryNumberItems
{
    use AsAction;
    use WithAttributes;
    use WithRecipientsInput;

    private bool $asAction = false;

    public function handle(Query $query): int
    {
        return rand(0, 99999);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }


    /**
     * @throws \Exception
     */
    public function asController(Shop $shop, Query $query, ActionRequest $request): array
    {
        $count = $this->handle($query);

        return [
            'count' => $count
        ];
    }
}
