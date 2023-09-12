<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 15:25:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Organisation\Organisation;
use Lorisleiva\Actions\ActionRequest;

class UpdateOrganisation
{
    use WithActionUpdate;


    public function handle(Organisation $organisation, array $modelData): Organisation
    {
        return $this->update($organisation, $modelData, ['data']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("sysadmin.edit");
    }


    public function rules(): array
    {
        return [
            'name'   => ['sometimes','required','max:64']
        ];
    }

    public function asController(ActionRequest $request): Organisation
    {
        $this->fillFromRequest($request);
        return $this->handle(organisation(), $this->validateAttributes());
    }



}
