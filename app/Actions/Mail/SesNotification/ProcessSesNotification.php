<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:07:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\SesNotification;

use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\SesNotification;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessSesNotification
{
    use AsAction;

    public function handle(SesNotification $sesNotification): void
    {

        $dispatchedEmail=DispatchedEmail::where('provider_message_id', $sesNotification->message_id.'x')->first();

        if($dispatchedEmail) {
            $dispatchedEmail->events()->create(
                [

                ]
            );

        }


    }


    public string $commandSignature = 'ses-notify:process {id?} {--l|limit=}';


    public function asCommand(Command $command): int
    {
        if($command->argument('id')) {
            try {
                $sesNotification = SesNotification::find('slug', $command->argument('id'));
                $command->line($sesNotification->message_id);
                $this->handle($sesNotification);
            } catch (Exception $e) {
                $command->error($e->getMessage());

                return 1;
            }
        }

        foreach(SesNotification::paginate($command->option('limit')??10) as  $sesNotification) {
            $command->line($sesNotification->message_id);
            $this->handle($sesNotification);
        }

        return 0;
    }


}
