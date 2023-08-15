<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class AttachContentBlockToPortfolioWebsite
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;
    public function handle(PortfolioWebsite $portfolioWebsite, ContentBlock $contentBlock): Model
    {
        $portfolioWebsite->contentBlocks()->attach([$contentBlock->id]);

        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ContentBlock $contentBlock, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($portfolioWebsite, $contentBlock);
    }
    public function action(PortfolioWebsite $portfolioWebsite, ContentBlock $contentBlock): Model
    {


        return $this->handle($portfolioWebsite, $contentBlock);
    }
}
