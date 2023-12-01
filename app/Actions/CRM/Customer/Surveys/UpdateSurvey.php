<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Surveys;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Survey;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateSurvey
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Survey $survey, array $modelData): Survey
    {
        return $this->update($survey, $modelData);
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
            'name' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function asController(Survey $survey, ActionRequest $request): Survey
    {
        $this->fillFromRequest($request);

        return $this->handle($survey, $this->validateAttributes());
    }


    public function action(Survey $survey, $objectData): Survey
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($survey, $validatedData);
    }
}
