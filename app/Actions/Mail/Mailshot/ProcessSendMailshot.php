<?php

namespace App\Actions\Mail\Mailshot;

use App\Actions\Helpers\Query\BuildQuery;
use App\Actions\Traits\WithCheckCanSendEmail;
use App\Helpers\ArrayWIthProbabilities;
use App\Models\Helpers\Query;
use App\Models\Mail\Email;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Mailshot;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessSendMailshot
{
    use AsAction;
    use WithCheckCanSendEmail;

    public function handle(Mailshot $mailshot): void
    {
        $query = Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));

        $queryBuilder = BuildQuery::run($query);


        $channel = 1;
        $counter = 1;
        $limit   = app()->isProduction() ? null : config('mail.devel.max_mailshot_recipients');

        $queryBuilder->chunk(
            1000,
            function ($recipients) use ($mailshot, &$counter, &$channel, $limit) {


                $chunkCount = 0;

                foreach ($recipients as $recipient) {
                    if (!$this->canSend($recipient)) {
                        continue;
                    }

                    if (!is_null($limit) and $counter > $limit) {
                        break;
                    }


                    $recipientExists = $mailshot->recipients()->where('recipient_id', $recipient->id)->where('recipient_type', class_basename($recipient))->exists();

                    if (!$recipientExists) {
                        if (app()->environment('production')) {
                            $emailAddress = $recipient->email;
                        } else {
                            $prefixes     = ['success' => 50, 'bounce' => 30, 'complaint' => 6, 'suppressionlist' => 2, 'ooto' => 2];
                            $prefix       = ArrayWIthProbabilities::make()->getRandomElement($prefixes);
                            $emailAddress = "$prefix+$recipient->slug@simulator.amazonses.com";
                        }

                        $email = Email::firstOrCreate(['address' => $emailAddress]);

                        $dispatchedEmail = DispatchedEmail::create(
                            [
                                'email_id' => $email->id,
                                'date'     => now()
                            ]
                        );


                        $mailshot->recipients()->updateOrCreate(
                            [
                                'dispatched_email_id' => $dispatchedEmail->id
                            ],
                            [
                                'recipient_id'   => $recipient->id,
                                'recipient_type' => class_basename($recipient),
                                'channel'        => $channel,
                            ]
                        );
                        $chunkCount++;
                        // print "$channel $chunkCount $counter $dispatchedEmail->id {$dispatchedEmail->email->address}\n";
                    }
                    $counter++;
                }


                if ($chunkCount) {

                    // $channelLabel=Str::slug(SpellNumber::value($channel)->toLetters());

                    $mailshot->update(
                        [
                            "channels->{$channel}->ready"=> now(),
                            "channels->{$channel}->count"=> $chunkCount

                        ]
                    );
                    MailshotSendEmailChunk::dispatch($mailshot, $channel);
                }

                $channel++;
            }
        );
    }


    public string $commandSignature = 'mailshot:send {mailshot}';


    public function asCommand(Command $command): int
    {
        try {
            $mailshot = Mailshot::where('slug', $command->argument('mailshot'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }


        $this->handle($mailshot);

        return 0;
    }


}
