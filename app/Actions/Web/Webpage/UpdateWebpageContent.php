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


    public function handle(Webpage $webpage, array $content): Webpage
    {
        $snapshot = $webpage->unpublishedSnapshot;

        $snapshot->update(
            [
                'layout' => [
                    'src'  => $content['data'],
                    'html' => $content['pagesHtml'],
                ]
            ]
        );

        $isDirty = true;
        if ($webpage->published_checksum == md5(json_encode($snapshot->layout))) {
            $isDirty = false;
        }

        $webpage->update(
            [
                'is_dirty' => $isDirty
            ]
        );

        return $webpage;
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

        $webpage = $this->handle($webpage, $request->validated());

        return [
            'isDirty' => $webpage->is_dirty
        ];
    }


}
