<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 13:02:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail;

use App\Enums\EnumHelperTrait;

enum MailshotStateEnum: string
{
    use EnumHelperTrait;

    case IN_PROCESS = 'in-process';
    case READY      = 'ready';
    case SCHEDULED  = 'scheduled';
    case SENDING    = 'sending';
    case SENT       = 'sent';
    case CANCELLED  = 'cancelled';
    case STOPPED    = 'stopped';

    public static function labels(): array
    {
        return [
            'in-process' => __('In process'),
            'ready'      => __('Ready'),
            'scheduled'  => __('Scheduled'),
            'sending'    => __('Sending'),
            'sent'       => __('Sent'),
            'cancelled'  => __('Cancelled'),
            'stopped'    => __('Stopped'),
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
            'ready' => [

                'tooltip' => __('Ready'),
                'icon'    => 'fal fa-spell-check',
                'class'   => 'text-green-500'


            ],
            'scheduled' => [

                'tooltip' => __('Scheduled'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'


            ],
            'sending'        => [

                'tooltip' => __('sending'),
                'icon'    => 'fal fa-paper-plane',
                'class'   => 'animate-pulse'

            ],
            'sent'     => [

                'tooltip' => __('sent'),
                'icon'    => 'fal fa-paper-plane',
                'class'   => 'text-green-600'
            ],

        ];
    }
}
