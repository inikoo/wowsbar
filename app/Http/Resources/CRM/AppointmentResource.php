<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 21:20:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Enums\CRM\Appointment\AppointmentStateEnum;
use App\Models\CRM\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Appointment $appointment */
        $appointment = $this;


        $organisationUserName        = '';
        $organisationUserAvatar      = null;
        if ($appointment->organisation_user_id) {


            $organisationUserName        = $appointment->organisationUser->contact_name;
            $organisationUserAvatar      = $appointment->organisationUser->avatarImageSources(48, 48);
        }


        return [
            'slug'             => $appointment->slug,
            'published_at'     => $appointment->published_at,
            'published_until'  => $appointment->published_until,
            'layout'           => $appointment->layout,
            'publisher'        => $organisationUserName,
            'publisher_avatar' => $organisationUserAvatar,
            'state'            => match ($appointment->state) {
                AppointmentStateEnum::BOOKED => [
                    'tooltip' => __('live'),
                    'icon'    => 'fal fa-broadcast-tower',
                    'class'   => 'text-green-600 animate-pulse'
                ],
                AppointmentStateEnum::OVERDUE => [
                    'tooltip' => __('unpublished'),
                    'icon'    => 'fal fa-seedling',
                    'class'   => 'text-indigo-500'
                ],
                AppointmentStateEnum::ONGOING => [
                    'tooltip' => __('retired'),
                    'icon'    => 'fal fa-ghost'
                ],
                AppointmentStateEnum::FINISH => [
                    'tooltip' => __('retired'),
                    'icon'    => 'fal fa-ghost'
                ],
                AppointmentStateEnum::CANCEL => [
                    'tooltip' => __('retired'),
                    'icon'    => 'fal fa-ghost'
                ],
                default=> []

            },
        ];
    }
}
