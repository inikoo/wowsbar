<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Dec 2023 22:22:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Events\BroadcastNotification;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

class SendNotification
{
    use AsAction;

    public function handle(string $title, string $text): void
    {
        BroadcastNotification::dispatch($title, $text);
    }

    public string $commandSignature = 'send:notification {message?}';

    public function asCommand(Command $command): int
    {
        if ($command->hasArgument('message')) {
            $title = 'Hey';
            $text  = $command->argument('message');
        } else {
            $title = $command->ask('Title');
            $text  = $command->ask('Text');
        }

        $this->handle($title, $text);

        return 0;
    }

}
