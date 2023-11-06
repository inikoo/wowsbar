<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 18:40:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Models\Helpers\Query;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateQueryCount
{
    use AsAction;

    private bool $asAction = false;


    public function handle(Query $query)
    {

        $queryBuilder=BuildQuery::run($query);

        $numberItems=$queryBuilder->count();

        $query->update(
            [
                'number_items'=> $numberItems,
                'counted_at'  => now()

            ]
        );

        return $numberItems;

    }

    public string $commandSignature = 'query:count {slug}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $query = Query::where('slug', $command->argument('slug'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->handle($query);

        return 0;
    }

}
