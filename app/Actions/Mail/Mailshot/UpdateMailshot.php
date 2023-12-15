<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;

class UpdateMailshot
{
    use WithActionUpdate;

    public function handle(Mailshot $mailshot, array $modelData): Mailshot
    {
        return $this->update($mailshot, $modelData, ['data']);
    }


    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function shopProspects(Shop $shop, Mailshot $mailshot, ActionRequest $request): Mailshot
    {
        $this->fillFromRequest($request);
        $validatedData = $this->validateAttributes();

        return $this->handle($mailshot, $validatedData);
    }
}
