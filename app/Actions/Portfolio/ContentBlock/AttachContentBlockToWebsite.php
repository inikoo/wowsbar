<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class AttachContentBlockToWebsite
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;
    public function handle(Website $website, ContentBlock $contentBlock): Model
    {
        $website->website()->attach([$contentBlock->id]);

        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function asController(Website $website, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($website, $request->validated());
    }
    public function action(Website $website, array $objectData): Model
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $validatedData);
    }
}
