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

    public function handle(Website $website, array $content): Website
    {
        $snapshot = $website->unpublishedHeaderSnapshot;
        $snapshot->update(
            [
                'layout' => [
                    'src'  => $content['data'],
                    'html' => $content['pagesHtml'],
                ]
            ]
        );

        $isDirty = true;
        if ($website->published_header_checksum == md5(json_encode($snapshot->layout))) {
            $isDirty = false;
        }

        $website->update(
            [
                'header_is_dirty' => $isDirty
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
            'isDirty' => $website->header_is_dirty
        ];
    }


}
