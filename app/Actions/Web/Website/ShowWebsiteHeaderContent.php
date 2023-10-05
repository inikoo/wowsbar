<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 10:45:41 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Lorisleiva\Actions\ActionRequest;

class ShowWebsiteHeaderContent
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): array
    {
        return $website->header_content;
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


    public function asController(Website $website, ActionRequest $request): array
    {
        $request->validate();

        return $this->handle($website);
    }


}
