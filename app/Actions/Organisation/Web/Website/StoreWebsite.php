<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 13:45:07 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website;

use App\Models\Organisation\Web\Website;
use App\Rules\CaseSensitive;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreWebsite
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;


    public function handle(array $modelData): Website
    {
        data_set($modelData, 'domain', config('app.domain'));

        /** @var Website $website */
        $website = organisation()->website()->create($modelData);
        $website->webStats()->create();
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("website.edit");
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', new CaseSensitive('websites')],
            'code'   => ['required', 'unique:tenant.websites','max:8'],
            'name'   => ['required']
        ];
    }


    public function afterValidator(Validator $validator): void
    {
        if (organisation()->website) {
            $validator->errors()->add('domain', 'This organisation already have a website');
        }
    }


    public function action(array $objectData): Website
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }
}
