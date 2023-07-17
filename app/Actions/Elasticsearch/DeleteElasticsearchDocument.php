<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Elasticsearch;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsObject;

class DeleteElasticsearchDocument
{
    use AsCommand;

    public string $commandSignature   = 'elasticsearch:destroy {indexName}';
    public function getCommandDescription():string
    {
        return  'Delete the data based on index name';
    }



    public function asCommand(Command $command): int
    {

        $indexName=$command->argument('indexName');
        $client = BuildElasticsearchClient::run();

        try {
            $params = [
                'index' => $indexName
            ];

            $response = $client->indices()->delete($params);

            if ($response['acknowledged']) {
                $command->line("Data successfully deleted 🧼");

                return 0;
            }

            $command->error("Unknown error. Delete data failed");

            return false;
        } catch(Exception $exception) {
            $msg=$exception->getMessage();
            $command->error( "Delete data failed ($msg)");

            return 1;
        }

    }
}
