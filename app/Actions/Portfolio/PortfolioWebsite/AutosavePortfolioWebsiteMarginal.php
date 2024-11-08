<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 10:05:33 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Helpers\Snapshot\StoreWebsiteSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Helpers\Snapshot\SnapshotStateEnum;
use App\Events\BroadcastPreviewHeaderFooter;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class AutosavePortfolioWebsiteMarginal
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(PortfolioWebsite $portfolioWebsite, string $marginal, array $modelData): void
    {
        $layout      = Arr::get($modelData, 'layout');
        
        if (in_array($marginal, ['header', 'footer'])) {
            $updateData = [
                "compiled_layout->$marginal"      => $layout,
                "published_{$marginal}_checksum"  => md5(json_encode($layout)),
            ];

        } else {
            $updateData = [
                "compiled_layout->$marginal"     => $layout
            ];
        }
       
        $portfolioWebsite->update($updateData);

       /*  BroadcastPreviewHeaderFooter::dispatch($portfolioWebsite); */
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'publisher_id'   => $request->user()->id,
                'publisher_type' => 'OrganisationUser'
            ]
        );
    }

    public function rules(): array
    {
        return [
            'comment'        => ['sometimes', 'required', 'string', 'max:1024'],
            'publisher_id'   => ['sometimes'],
            'publisher_type' => ['sometimes', 'string'],
            'layout'         => ['sometimes', 'array'],
        ];
    }

    public function header(PortfolioWebsite $portfolioWebsite, ActionRequest $request): string
    {
        $request->validate();

        $this->handle($portfolioWebsite, 'header', $request->validated());

        return "🚀";
    }

    public function footer(PortfolioWebsite $portfolioWebsite, ActionRequest $request): string
    {
        $this->isAction = true;
        $request->validate();
        $this->handle($portfolioWebsite, 'footer', $request->validated());

        return "🚀";
    }

    public function action(PortfolioWebsite $portfolioWebsite, $marginal, $modelData): string
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        $this->handle($portfolioWebsite, $marginal, $validatedData);

        return "🚀";
    }
}
