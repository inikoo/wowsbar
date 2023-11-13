<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 13 Nov 2023 13:34:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Models\Mail\Mailshot;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

class UpdateMailshotSentState
{
    use AsAction;

    public function handle(Mailshot $mailshot): array
    {
        if (!$mailshot->recipients_stored_at) {
            return [
                'msg'=> 'emails still processing'
            ];
        }
        $count = count($mailshot->channels);
        if ($count == 0) {
            return [
                'error'=> true,
                'msg'  => 'no channels found'
            ];
        }

        print_r($mailshot->channels);

        $sent   = true;
        $sentAt = null;


        foreach ($mailshot->channels as $chanel) {



            if (Arr::exists($chanel, 'sent')) {
                $chanelSentAt = Arr::get($chanel, 'sent');
                if (is_null($sentAt)) {
                    $sentAt = new Carbon($chanelSentAt);
                } else {
                    $chanelSentAtDate = new Carbon($chanelSentAt);
                    if ($sentAt->lt($chanelSentAtDate)) {
                        $sentAt = $chanelSentAtDate;
                    }
                }
            } else {
                $sent = false;
                break;
            }
        }

        return [];
    }

    public string $commandSignature = 'mailshot:sent-state {mailshot}';


    public function asCommand(Command $command): int
    {
        try {
            $mailshot = Mailshot::where('slug', $command->argument('mailshot'))->firstOrFail();
        } catch (Exception) {
            $command->error('Mailshot not found');

            return 1;
        }
        $res=$this->handle($mailshot);

        $command->line(Arr::get($res, 'msg', ''));

        return 0;
    }

}
