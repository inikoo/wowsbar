<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 10:44:19 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteFooterContent
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $content): Website
    {

        $snapshot = $website->unpublishedFooterSnapshot;
        $snapshot->update(
            [
                'layout' => [
                    'src' => $content['data'],
                    'html'=> $content['pagesHtml'],
                ]
            ]
        );
        $isDirty = true;
        if ($website->published_footer_checksum == md5(json_encode($snapshot->layout))) {
            $isDirty = false;
        }

        $website->update(
            [
                'footer_is_dirty' => $isDirty
            ]
        );

        return $website;

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

    public function asController(Website $website, ActionRequest $request): array
    {
        $request->validate();
        $website = $this->handle($website, $request->validated());

        return [
            'isDirty' => $website->footer_is_dirty
        ];
    }


}
