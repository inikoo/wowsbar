<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Surveys;

use App\Models\Market\Shop;
use App\Models\Survey;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreSurvey
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle(Shop $shop, array $modelData): Survey
    {
        /** @var Survey */
        return $shop->surveys()->create($modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo('crm.edit');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function inShop(Shop $shop, ActionRequest $request): Survey
    {
        $this->fillFromRequest($request);
        $request->validate();

        return $this->handle($shop, $request->validated());
    }

    public function action(Shop $shop, $objectData): Survey
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($shop, $validatedData);
    }
}
