<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 30 Jun 2023 10:29:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Notifications;

use App\Actions\Mail\SesNotification\ProcessSesNotification;
use App\Models\Mail\SesNotification;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSnsNotification
{
    use AsAction;

    public function asController(): string
    {
        $message   = Message::fromRawPostData();
        $validator = new MessageValidator();

        if ($validator->isValid($message)) {
            if ($message['Type'] == 'SubscriptionConfirmation') {
                file_get_contents($message['SubscribeURL']);
            } elseif ($message['Type'] === 'Notification') {
                $messageData = json_decode($message['Message'], true);

                $type=Arr::get($messageData, 'notificationType');
                if($type=='notificationType') {
                    return 'ok';
                }

                if($messageId=Arr::get($messageData, 'mail.messageId')) {

                    $sesNotification=SesNotification::create(
                        [
                            'message_id'=> $messageId,
                            'data'      => $messageData
                        ]
                    );

                    ProcessSesNotification::dispatch($sesNotification);

                }





            }
        }
        return 'ok';
    }



}
