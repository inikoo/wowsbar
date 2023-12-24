<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateModel
{
    use AsAction;



    protected function getModel(string $slug)
    {
        return null;
    }

    protected function getAllModels()
    {
        return new Collection();
    }


    public function asCommand(Command $command): int
    {
        $exitCode = 0;
        if(!$command->argument('slugs')) {
            $this->loopAll($command);
        } else {

            foreach($command->argument('slugs') as $slug) {
                $model=$this->getModel($slug);
                $this->handle($model);
                $command->line(class_basename($model)." $model->name hydrated 💦");
            }
        }

        return $exitCode;

    }


    protected function loopAll(Command $command): void
    {
        $command->withProgressBar($this->getAllModels(), function ($model) {
            if ($model) {
                $this->handle($model);
            }
        });
        $command->info("");
    }
}
