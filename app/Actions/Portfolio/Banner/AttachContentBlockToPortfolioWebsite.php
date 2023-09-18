<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Models\Portfolio\Banner;
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
    public function handle(PortfolioWebsite $portfolioWebsite, Banner $contentBlock): Model
    {
        $portfolioWebsite->contentBlocks()->attach([$contentBlock->id]);

        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, Banner $contentBlock, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($portfolioWebsite, $contentBlock);
    }
    public function action(PortfolioWebsite $portfolioWebsite, Banner $contentBlock): Model
    {


        return $this->handle($portfolioWebsite, $contentBlock);
    }
}
