<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:01:05 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateBannerState
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Banner $contentBlock, array $modelData): Banner
    {
        switch ($modelData[BannerStateEnum::READY->value]) {
            case BannerStateEnum::READY->value:
                $modelData['ready_at'] = now();
                break;
            case BannerStateEnum::LIVE->value:
                $modelData['live_at'] = now();
                break;
            case BannerStateEnum::RETIRED->value:
                $modelData['retired_at'] = now();
                break;
        }

        $this->update($contentBlock, $modelData, ['data','layout']);

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'state' => ['sometimes', 'required', Rule::in(BannerStateEnum::values())]
        ];
    }

    public function asController(Banner $banner, $state): Banner
    {
        $this->setRawAttributes([
            'state' => $state
        ]);

        return $this->handle($banner, $this->validateAttributes());
    }

    public function action(Banner $contentBlock, $modelData): Banner
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($contentBlock, $validatedData);
    }

    public function jsonResponse(Banner $website): BannerResource
    {
        return new BannerResource($website);
    }
}
