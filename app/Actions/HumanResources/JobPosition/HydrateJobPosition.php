<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition;

use App\Actions\Traits\WithNormalise;
use App\Models\HumanResources\JobPosition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateJobPosition
{
    use AsAction;
    use WithNormalise;


    public function handle(int $jobPositionID): void
    {

        $jobPosition=JobPosition::find($jobPositionID);
        if($jobPosition) {
            $jobPosition->update(
                [
                    'number_employees' => DB::table('job_positionables')->where('job_position_id', $jobPosition->id)->count(),
                    'number_work_time' => DB::table('job_positionables')->where('job_position_id', $jobPosition->id)->sum('share'),
                ]
            );


            $this->updateNormalisedJobPositionsShare();
        }
    }

    private function updateNormalisedJobPositionsShare(): void
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
        foreach (JobPosition::all() as $jobPosition) {
            $share[$jobPosition->id] = $jobPosition->number_work_time;
        }

        return $this->normalise(collect($share));
    }

    public string $commandSignature = 'hydrate:job-positions {job-positions?*}';

    public function asCommand(Command $command): int
    {

        if(!$command->argument('job-positions')) {
            $jobPositions=JobPosition::all();
        } else {
            $jobPositions =  JobPosition::query()
                ->when($command->argument('job-positions'), function ($query) use ($command) {
                    $query->whereIn('slug', $command->argument('job-positions'));
                })
                ->cursor();
        }


        $exitCode = 0;

        foreach ($jobPositions as $jobPosition) {

            $this->handle($jobPosition);
            $command->line("Jon position $jobPosition->name hydrated 💦");

        }

        return $exitCode;
    }

}
