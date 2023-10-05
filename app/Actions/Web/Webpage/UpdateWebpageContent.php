<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 17:24:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Models\Web\Webpage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateWebpageContent
{
    use AsAction;
    use WithAttributes;


    public function handle(Webpage $webpage, array $data): void
    {
        $webpage->update(
            [
                'content'          => $data['data'],
                'compiled_content' => $data['pagesHtml']
            ]
        );
        $webpage->update(
            [
                'compiled_content' => $webpage->getCompiledContent()
            ]
        );

    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'data'      => ['required', 'array'],
            'pagesHtml' => ['required', 'array'],

        ];
    }

    public function asController(Webpage $webpage, ActionRequest $request): string
    {
        $request->validate();

         $this->handle($webpage, $request->validated());

         return '👍';
    }


}
