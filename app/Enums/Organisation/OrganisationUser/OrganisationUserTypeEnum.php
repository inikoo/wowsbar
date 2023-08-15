<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\OrganisationUser;

use App\Enums\EnumHelperTrait;

enum OrganisationUserTypeEnum: string
{
    use EnumHelperTrait;

    case EMPLOYEE = 'employee';
    case GUEST    = 'guest';


    public static function labels(): array
    {
        return [
            'employee' => __('Employee'),
            'guest'    => __('Guest'),
        ];
    }

    public static function count(): array
    {
        $stats = organisation()->stats;

        return [
            'employee' => $stats->number_users_type_employee,
            'guest'    => $stats->number_users_type_guest,
        ];
    }


}
