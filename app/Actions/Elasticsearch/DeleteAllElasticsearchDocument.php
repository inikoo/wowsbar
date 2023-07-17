<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Elasticsearch;

use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsCommand;
use stdClass;

class DeleteAllElasticsearchDocument
{
    use AsCommand;

    public string $commandSignature   = 'elasticsearch:flush';

    public function getCommandDescription():string
    {
        return  'Delete the all data elasticsearch';
    }

    public function asCommand(Command $command): int
    {
        $client = BuildElasticsearchClient::run();

        try {
            $response = $client->deleteByQuery([
                'index' => '_all',
                'body'  => [
                    'query' => [
                        'match_all' => new stdClass(),
                    ],
                ],
            ]);

            if ($response['deleted'] >= 0) {
                $command->line("🧼 Successfully {$response['deleted']} data deleted");
                return 0;
            }

            $command->error("Unknown error. Delete data failed");
            return 1;
        } catch(Exception $exception) {
            $msg=$exception->getMessage();
            $command->error( "Delete data failed ($msg)");

            return 1;
        }
    }
}
