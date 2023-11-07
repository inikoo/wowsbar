<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 14:08:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Prospect;

use App\Enums\EnumHelperTrait;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;

enum ProspectOutcomeStatusEnum: string
{
    use EnumHelperTrait;

    case HARD_FAIL          = 'hard-fail';
    case SOFT_FAIL          = 'soft-fail';
    case WAITING            = 'waiting';
    case SOFT_SUCCESS       = 'soft-success';
    case HARD_SUCCESS       = 'hard-success';

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

                'tooltip' => __('contacted'),
                'icon'    => 'fal fa-comment-dots',
                'class'   => 'text-green'

            ],
            'not-interested' => [

                'tooltip' => __('not interested'),
                'icon'    => 'fal fa-comment-exclamation',
                'class'   => 'text-red'

            ],
            'registered'     => [

                'tooltip' => __('registered'),
                'icon'    => 'fal fa-sign-in'

            ],
            'invoiced'       => [

                'tooltip' => __('invoiced'),
                'icon'    => 'fal fa-file-invoice'

            ],
            'bounced'        => [

                'tooltip' => __('invalid'),
                'icon'    => 'fal fa-poo',
                'class'   => 'text-red-300'
            ],


        ];
    }

    public static function count(Organisation|Shop $parent): array
    {
        $stats = $parent->crmStats;

        return [
            'no-contacted'   => $stats->number_prospects_state_no_contacted,
            'contacted'      => $stats->number_prospects_state_contacted,
            'not-interested' => $stats->number_prospects_state_not_interested,
            'registered'     => $stats->number_prospects_state_registered,
            'invoiced'       => $stats->number_prospects_state_invoiced,
            'bounced'        => $stats->number_prospects_state_bounced
        ];
    }

}
