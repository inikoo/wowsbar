<?php

namespace App\Actions\Mail\Mailshot;

use App\Actions\Helpers\Query\BuildQuery;
use App\Helpers\ArrayWIthProbabilities;
use App\Models\CRM\Customer;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use App\Models\Mail\Email;
use App\Models\Mail\EmailDelivery;
use App\Models\Mail\Mailshot;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessSendMailshot
{
    use AsAction;

    public function handle(Mailshot $mailshot): void
    {
        $query = Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));

        $queryBuilder = BuildQuery::run($query);

        $channel = 1;
        $queryBuilder->chunk(
            10,
            function ($recipients) use ($mailshot, &$channel) {
                print "$channel\n";
                foreach ($recipients as $recipient) {
                    if ($this->canSend($recipient)) {


                        if(app()->environment('production')) {
                            $emailAddress= $recipient->email;
                        } else {

                            $prefixes=['success'=>50, 'bounce'=>30, 'complaint'=>6,'suppressionlist'=>2,'ooto'=>2];


                            $prefix=ArrayWIthProbabilities::make()->getRandomElement($prefixes);


                            $emailAddress="$prefix+$recipient->slug@simulator.amazonses.com";
                        }

                        $email        = Email::firstOrCreate(['address' => $emailAddress]);
                        $emailDelivery=EmailDelivery::create(
                            [
                                'email_id'=> $email->id
                            ]
                        );

                        $mailshot->recipients()->updateOrCreate(
                            [
                                'email_delivery_id' => $emailDelivery->id
                            ],
                            [
                                'recipient_id'   => $recipient->id,
                                'recipient_type' => class_basename($recipient),
                                'channel'        => $channel
                            ]
                        );
                    }
                }
                SendEmailChunk::run($mailshot, $channel);

                $channel++;
            }
        );
    }

    protected function canSend(Prospect|Customer $recipient): bool
    {
        return match (class_basename($recipient)) {
            'Prospect' => $this->canSendProspect($recipient),
            'Customer' => $this->canSendCustomer($recipient)
        };
    }

    protected function canSendCustomer(Customer $customer): bool
    {


        return false;
    }
    protected function canSendProspect(Prospect $prospect): bool
    {
        if ($prospect->dont_contact_me) {
            return false;
        }

        if (!filter_var($prospect->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public string $commandSignature = 'mailshot:store-recipients {mailshot}';


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
