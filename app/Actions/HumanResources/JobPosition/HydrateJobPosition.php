<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition;

use App\Actions\HydrateModel;
use App\Actions\Traits\WithNormalise;
use App\Models\HumanResources\JobPosition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HydrateJobPosition extends HydrateModel
{
    use WithNormalise;

    public string $commandSignature = 'hydrate:job-positions {tenants?*} {--i|id=}';

    public function handle(JobPosition $jobPosition): void
    {
        $jobPosition->update(
            [
                'number_employees' => DB::table('employee_job_position')->where('job_position_id', $jobPosition->id)->count(),
                'number_work_time' => DB::table('employee_job_position')->where('job_position_id', $jobPosition->id)->sum('share'),
            ]
        );


        $this->updateNormalisedJobPositionsShare();
    }

    private function updateNormalisedJobPositionsShare()
    {
        foreach ($this->getNormalisedJobPositionsShare() as $id => $share) {
            JobPosition::find($id)->update(
                [
                    'share_work_time' => $share
                ]
            );
        }
    }

    private function getNormalisedJobPositionsShare(): array
    {
        $share = [];
        /** @var \App\Models\HumanResources\JobPosition $jobPosition */
        foreach (JobPosition::all() as $jobPosition) {
            $share[$jobPosition->id] = $jobPosition->number_work_time;
        }

        return $this->normalise(collect($share));
    }

    protected function getModel(int $id): JobPosition
    {
        return JobPosition::findOrFail($id);
    }

    protected function getAllModels(): Collection
    {
        return JobPosition::all();
    }
}
