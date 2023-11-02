<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 21 Jun 2023 08:44:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Prospect;

use App\Enums\EnumHelperTrait;

enum ProspectStateEnum: string
{
    use EnumHelperTrait;

    case NO_CONTACTED   = 'no-contacted';
    case CONTACTED      = 'contacted';
    case NOT_INTERESTED = 'not-interested';
    case REGISTERED     = 'registered';
    case INVOICED       = 'invoiced';
    case BOUNCED        = 'bounced';

    public static function labels(): array
    {
        return [
            'no-contacted'   => __('No contacted'),
            'contacted'      => __('Contacted'),
            'not-interested' => __('Not interested'),
            'registered'     => __('Registered'),
            'invoiced'       => __('Invoiced'),
            'bounced'        => __('Invalid'),
        ];
    }

    public static function stateIcon(): array
    {
        return [
            'no-contacted'   => [

                'tooltip' => __('no contacted'),
                'icon'    => 'fal fa-seedling',
                'class'   => 'text-indigo-500'


            ],
            'contacted'      => [

                'tooltip' => __('live'),
                'icon'    => 'fal fa-comment-dots',
                'class'   => 'text-green'

            ],
            'not-interested' => [

                'tooltip' => __('Not interested'),
                'icon'    => 'fal fa-comment-exclamation',
                'class'   => 'text-red'

            ],
            'registered'     => [

                'tooltip' => __('registered'),
                'icon'    => 'fal fa-sign-in'

            ],
            'invoiced'       => [

                'tooltip' => __('invoiced'),
                'icon'    => 'fal fa-invoice'

            ],
            'bounced'        => [

                'tooltip' => __('bounced'),
                'icon'    => 'fal fa-poo',
                'class'   => 'text-red-200'
            ],


        ];
    }

}
