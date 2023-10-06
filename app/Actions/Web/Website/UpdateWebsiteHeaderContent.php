<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 10:44:27 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteHeaderContent
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $content): void
    {

        $snapshot = $website->unpublishedHeaderSnapshot;
        $snapshot->update(
            [
                'layout' => [
                    'src' => $content['data'],
                    'html'=> $content['pagesHtml'],
                ]
            ]
        );


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
            'data'      => ['required', 'array'],
            'pagesHtml' => ['required', 'array'],
        ];
    }

    public function asController(Website $website, ActionRequest $request): string
    {
        $request->validate();
        $this->handle($website, $request->validated());
        return "👍";
    }


}
