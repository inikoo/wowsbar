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

class UpdateWebpageBlocks
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Webpage $webpage, array $blocks): Webpage
    {
        dd($webpage, $blocks);
        $webpage->update(
            [
                'blocks' => $blocks
            ]
        );
        $webpage->update(
            [
                'compiled_content' => $webpage->getCompiledContent()
            ]
        );

        return $webpage;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'blocks' => ['required', 'array'],

        ];
    }

    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $request->validate();

        return $this->handle($webpage, $request->validated());
    }


}
