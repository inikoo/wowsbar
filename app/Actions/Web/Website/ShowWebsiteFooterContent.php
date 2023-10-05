<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 10:45:41 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Accounting\PaymentResource;
use App\Models\Accounting\Payment;
use App\Models\Web\Website;
use Lorisleiva\Actions\ActionRequest;

class ShowWebsiteFooterContent
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        return $website->footer_content;
    }


    public function jsonResponse(array $content): false|string
    {
        return json_encode($content);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }


    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website);
    }


}
