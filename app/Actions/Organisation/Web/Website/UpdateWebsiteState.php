<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:59:00 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteState
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $modelData): Website
    {
        return $this->update($website, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("supervisor.website");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if (!$request->exists('status') and $request->has('state')) {
            $status = match ($request->get('state')) {
                WebsiteStateEnum::LIVE->value                                     => true,
                default                                                           => false
            };
            $request->merge(['status' => $status]);
        }
    }


    public function rules(): array
    {
        return [
            'state'  => ['required', new Enum(WebsiteStateEnum::class)],
            'status' => ['required', 'boolean'],

        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();
        return $this->handle($website, $request->validated());
    }


}
