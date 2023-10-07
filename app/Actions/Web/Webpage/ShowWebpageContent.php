<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 17:24:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Webpage;
use Lorisleiva\Actions\ActionRequest;

class ShowWebpageContent
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Webpage $webpage): array
    {
        return $webpage->unpublishedSnapshot->layout;
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


    public function asController(Webpage $webpage, ActionRequest $request): array
    {
        $request->validate();

        return $this->handle($webpage);
    }


}
