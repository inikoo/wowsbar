<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:07:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\SesNotification;

use App\Actions\Leads\Prospect\UpdateProspect;
use App\Enums\CRM\Prospect\ProspectBounceStatusEnum;
use App\Enums\Mail\DispatchedEmailEventTypeEnum;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\SesNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessSesNotification
{
    use AsAction;

    public function handle(SesNotification $sesNotification): void
    {

        // if(Arr::get($sesNotification->data, 'notificationType')){
        //     $sesNotification->delete();
        // }


        $dispatchedEmail = DispatchedEmail::where('provider_message_id', $sesNotification->message_id)->first();

        switch (Arr::get($sesNotification->data, 'eventType')) {
            case 'Reject':

                if ($dispatchedEmail->state == DispatchedEmailStateEnum::READY) {
                    $dispatchedEmail->update(
                        [
                            'state' => DispatchedEmailStateEnum::REJECT
                        ]
                    );
                }
                $sesNotification->delete();

                return;
            case 'Delivery':
                $type = DispatchedEmailEventTypeEnum::DELIVERY;
                $date = Arr::get($sesNotification->data, 'delivery.timestamp');
                $data = Arr::only($sesNotification->data['delivery'], ['remoteMtaIp', 'smtpResponse']);
                break;
            case 'Complaint':
                $type = DispatchedEmailEventTypeEnum::DELIVERY;
                $date = Arr::get($sesNotification->data, 'complaint.timestamp');
                $data = Arr::only($sesNotification->data['complaint'], ['userAgent', 'complaintSubType', 'complaintFeedbackType']);
                break;
            case 'Bounce':
                $type = DispatchedEmailEventTypeEnum::BOUNCE;
                $date = Arr::get($sesNotification->data, 'bounce.timestamp');
                $data = Arr::only($sesNotification->data['bounce'], ['bounceType', 'bounceSubType', 'reportingMTA']);

                if($dispatchedEmail->mailshotRecipient->recipient_type=='Prospect') {
                    UpdateProspect::run(
                        $dispatchedEmail->mailshotRecipient->recipient,
                        [
                            'bounce_status'=>
                                Arr::get($sesNotification->data, 'bounce.bounceType') == 'Permanent' ?
                                    ProspectBounceStatusEnum::HARD_BOUNCE : ProspectBounceStatusEnum::SOFT_BOUNCE
                        ]
                    );
                }


                if (Arr::get($sesNotification->data, 'bounce.bounceType') == 'Permanent') {

                    $dispatchedEmail->email()->update(
                        [
                            'is_hard_bounced'  => true,
                            'hard_bounce_type' => Arr::get($sesNotification->data, 'bounce.bounceSubType')
                        ]
                    );

                }

                break;
            default:
                dd($sesNotification->data);

                return;
        }

        $sesNotification->delete();


        if ($dispatchedEmail) {
            $eventData = [
                'type' => $type,
                'date' => $date,
                'data' => $data
            ];

            //  dd($eventData);

            $dispatchedEmail->events()->create($eventData);
        }
    }


    public string $commandSignature = 'ses-notify:process {id?}';


    public function asCommand(Command $command): int
    {
        if ($command->argument('id')) {
            try {
                $sesNotification = SesNotification::find('slug', $command->argument('id'));
                $command->line($sesNotification->message_id);
                $this->handle($sesNotification);

                return 0;
            } catch (Exception $e) {
                $command->error($e->getMessage());

                return 1;
            }
        }
        $command->line('Number ses notifications to process '.SesNotification::count());

        foreach (SesNotification::all() as $sesNotification) {
            //$command->line($sesNotification->message_id);
            $this->handle($sesNotification);
        }

        return 0;
    }


}
