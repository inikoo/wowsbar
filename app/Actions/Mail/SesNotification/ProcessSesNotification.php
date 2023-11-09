<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:07:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\SesNotification;

use App\Actions\Leads\Prospect\UpdateProspect;
use App\Actions\Mail\DispatchedEmail\UpdateDispatchedEmail;
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

    public function handle(SesNotification $sesNotification): ?array
    {
        $dispatchedEmail = DispatchedEmail::where('provider_message_id', $sesNotification->message_id)->first();

        switch (Arr::get($sesNotification->data, 'eventType')) {
            case 'Bounce':
                $type = DispatchedEmailEventTypeEnum::BOUNCE;
                $date = Arr::get($sesNotification->data, 'bounce.timestamp');
                $data = Arr::only($sesNotification->data['bounce'], ['bounceType', 'bounceSubType', 'reportingMTA']);

                $isHardBounce = Arr::get($sesNotification->data, 'bounce.bounceType') == 'Permanent';

                if ($dispatchedEmail->mailshotRecipient->recipient_type == 'Prospect') {
                    UpdateProspect::run(
                        $dispatchedEmail->mailshotRecipient->recipient,
                        [
                            'bounce_status' =>
                                $isHardBounce ? ProspectBounceStatusEnum::HARD_BOUNCE : ProspectBounceStatusEnum::SOFT_BOUNCE
                        ]
                    );
                }


                if ($isHardBounce) {
                    $dispatchedEmail->email()->update(
                        [
                            'is_hard_bounced'  => true,
                            'hard_bounce_type' => Arr::get($sesNotification->data, 'bounce.bounceSubType')
                        ]
                    );
                }

                UpdateDispatchedEmail::run(
                    $dispatchedEmail,
                    [
                        'state' => $isHardBounce ? DispatchedEmailStateEnum::HARD_BOUNCE : DispatchedEmailStateEnum::SOFT_BOUNCE,
                        'date'  => $date,
                    ]
                );

                break;
            case 'Complaint':
                $type = DispatchedEmailEventTypeEnum::COMPLAIN;
                $date = Arr::get($sesNotification->data, 'complaint.timestamp');
                $data = Arr::only($sesNotification->data['complaint'], ['userAgent', 'complaintSubType', 'complaintFeedbackType']);

                UpdateDispatchedEmail::run(
                    $dispatchedEmail,
                    [
                        'state' => DispatchedEmailStateEnum::SPAM,
                        'date'  => $date,
                    ]
                );
                break;
            case 'Delivery':
                $type = DispatchedEmailEventTypeEnum::DELIVERY;
                $date = Arr::get($sesNotification->data, 'delivery.timestamp');
                $data = Arr::only($sesNotification->data['delivery'], ['remoteMtaIp', 'smtpResponse']);

                UpdateDispatchedEmail::run(
                    $dispatchedEmail,
                    [
                        'state'        => DispatchedEmailStateEnum::DELIVERED,
                        'date'         => $date,
                        'delivered_at' => $date,
                        'is_delivered' => true
                    ]
                );
                break;
            case 'Reject':

                if ($dispatchedEmail->state == DispatchedEmailStateEnum::READY) {
                    UpdateDispatchedEmail::run(
                        $dispatchedEmail,
                        [
                            'state' => DispatchedEmailStateEnum::REJECTED,
                        ]
                    );
                }
                $sesNotification->delete();

                return null;


            case 'Open':
                $type = DispatchedEmailEventTypeEnum::OPEN;
                $date = Arr::get($sesNotification->data, 'open.timestamp');
                $data = Arr::only($sesNotification->data['open'], ['ipAddress', 'userAgent']);

                UpdateDispatchedEmail::run(
                    $dispatchedEmail,
                    [
                        'state'   => DispatchedEmailStateEnum::OPENED,
                        'date'    => $date,
                        'is_open' => true
                    ]
                );

                break;
            case 'Click':
                $type = DispatchedEmailEventTypeEnum::CLICK;
                $date = Arr::get($sesNotification->data, 'click.timestamp');
                $data = Arr::only($sesNotification->data['click'], ['ipAddress', 'userAgent', 'link', 'linkTags']);

                UpdateDispatchedEmail::run(
                    $dispatchedEmail,
                    [
                        'state'      => DispatchedEmailStateEnum::CLICKED,
                        'date'       => $date,
                        'is_clicked' => true
                    ]
                );

                break;

            case 'DeliveryDelay':
                $type = DispatchedEmailEventTypeEnum::DELIVERY_DELAY;
                $date = Arr::get($sesNotification->data, 'deliveryDelay.timestamp');
                $data = Arr::only(
                    $sesNotification->data['deliveryDelay'],
                    ['delayType', 'expirationTime', 'reportingMTA']
                );

                break;

            default:
                return $sesNotification->data;
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

        return null;
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
            $pending = $this->handle($sesNotification);
            if ($pending) {
                print_r($pending);
            }
        }

        return 0;
    }


}
