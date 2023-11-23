<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 23 Nov 2023 11:29:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Prospect;

use App\Enums\EnumHelperTrait;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;

enum ProspectFailStatusEnum: string
{
    use EnumHelperTrait;

    case NA             = 'no-applicable';
    case NOT_INTERESTED = 'not-interested';

    case UNSUBSCRIBED = 'unsubscribed';

    case INVALID = 'invalid';

    public static function labels(): array
    {
        return [
            'no-applicable'  => __('NA'),
            'not-interested' => __('Not interested'),
            'unsubscribed'   => __('Unsubscribed'),
            'invalid'        => __('Invalid'),
        ];
    }

    public static function statusIcon(): array
    {
        return [
            'no-applicable' => [

                'tooltip' => __('NA'),
                'icon'    => 'fal fa-location-slash',


            ],

            'not-interested' => [

                'tooltip' => __('not interested'),
                'icon'    => 'fal fa-comment-exclamation',
                'class'   => 'text-red'

            ],

            'unsubscribed' => [

                'tooltip' => __('Unsubscribed'),
                'icon'    => 'fal fa-comment-slash',
                'class'   => 'text-red-300'
            ],

            'invalid' => [

                'tooltip' => __('invalid'),
                'icon'    => 'fal fa-exclamation-circle',
                'class'   => 'text-red-300'
            ],


        ];
    }

    public static function count(Organisation|Shop $parent): array
    {
        $stats = $parent->crmStats;

        return [
            'no-applicable'  => $stats->number_prospects_fail_status_no_applicable,
            'not-interested' => $stats->number_prospects_fail_status_not_interested,
            'unsubscribed'   => $stats->number_prospects_fail_status_unsubscribed,
            'invalid'        => $stats->number_prospects_fail_status_invalid,
        ];
    }

}
