<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Portfolio\ContentBlock\Hydrators\ContentBlockHydrateUniversalSearch;
use App\Models\Portfolio\ContentBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteContentBlock
{
    use AsController;
    use WithAttributes;

    public function handle(ContentBlock $contentBlock): ContentBlock
    {
        $contentBlock->delete();

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(ContentBlock $contentBlock, ActionRequest $request): ContentBlock
    {
        $request->validate();
        return $this->handle($contentBlock);
    }



    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('portfolio.websites.index');
    }

}
