<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteFooter
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $modelData): Website
    {
        return $this->update($website, $modelData, ['data']);
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
            'logo' => ['required','integer'],
            'type'=>['required','string'],
            'social'=>['required','array'],
            'columns'=>['required','array'],
            'copyright'=>['required','array'],
        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();

      //  dd($request->validated());

        return $this->handle($website, $request->validated());
    }


}
