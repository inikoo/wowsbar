<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 23:12:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail;

use App\Enums\EnumHelperTrait;

enum DispatchedEmailStateEnum: string
{
    use EnumHelperTrait;

    case READY         = 'ready';
    case ERROR         = 'error';
    case REJECT        = 'reject';

    case SENT        = 'sent';
    case HARD_BOUNCE = 'hard_bounce';
    case SOFT_BOUNCE = 'soft_bounce';
    case OPENED      = 'opened';

    case CLICKED      = 'clicked';
    case SPAM         = 'spam';
    case UNSUBSCRIBED = 'unsubscribed';


    public static function labels(): array
    {
        return [
            'ready'     => __('Ready to send'),
            'error'     => __('Error, count not send'),
            'sent'      => __('Sent'),
            'cancelled' => __('Cancelled'),
            'stopped'   => __('Stopped'),
        ];
    }

    public static function stateIcon(): array
    {
        return [
            'in-process' => [

                'tooltip' => __('In process'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'


            ],
            'ready'      => [

                'tooltip' => __('Ready'),
                'icon'    => 'fal fa-spell-check',
                'class'   => 'text-green-500'


            ],
            'scheduled'  => [

                'tooltip' => __('Scheduled'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'


            ],
            'live'       => [

                'tooltip' => __('live'),
                'icon'    => 'fal fa-broadcast-tower',
                'class'   => 'text-green-600 animate-pulse'

            ],
            'switch_off' => [

                'tooltip' => __('switch off'),
                'icon'    => 'fal fa-eye-slash'

            ],

        ];
    }
}
