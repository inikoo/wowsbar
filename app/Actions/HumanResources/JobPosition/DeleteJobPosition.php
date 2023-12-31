<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition;

use App\Models\HumanResources\JobPosition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteJobPosition
{
    use AsController;
    use WithAttributes;

    public function handle(JobPosition $jobPosition): JobPosition
    {
        $jobPosition->delete();

        return $jobPosition;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function asController(JobPosition $jobPosition, ActionRequest $request): JobPosition
    {
        $request->validate();

        return $this->handle($jobPosition);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('org.hr.job-positions.index');
    }

}
