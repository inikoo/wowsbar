<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Portfolio\ContentBlock\Elasticsearch\DeleteContentBlockElasticsearch;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateContentBlocks;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteContentBlock
{
    use AsAction;
    use AsController;
    use WithAttributes;

    public bool $isAction                 = false;
    public PortfolioWebsite|null $website = null;

    public function handle(ContentBlock $contentBlock): ContentBlock
    {
        $contentBlock->delete();

        TenantHydrateContentBlocks::dispatch(app('currentTenant'));
        DeleteContentBlockElasticsearch::run($contentBlock);

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->isAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function action(ContentBlock $contentBlock): ContentBlock
    {
        return $this->handle($contentBlock);
    }

    public function asController(ContentBlock $contentBlock, ActionRequest $request): ContentBlock
    {
        $request->validate();
        return $this->handle($contentBlock);
    }


    public function htmlResponse(): RedirectResponse
    {
        return back();
    }
}
