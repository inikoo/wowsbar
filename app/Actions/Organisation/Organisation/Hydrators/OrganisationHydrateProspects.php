<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
use App\Models\Leads\Prospect;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateProspects
{
    use AsAction;
    use WithEnumStats;

    public function getJobMiddleware(): array
    {
        return [(new WithoutOverlapping(1))->dontRelease()];
    }

    public function handle(): void
    {
        $stats = [
            'number_prospects'                 => Prospect::where('scope_type', 'Shop')->count(),
            'number_prospects_dont_contact_me' => Prospect::where('scope_type', 'Shop')->where('dont_contact_me', true)->count(),

        ];

        $stats = array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'state',
                enum: ProspectStateEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );

        $stats = array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'contacted_state',
                enum: ProspectContactedStateEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );

        $stats = array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'fail_status',
                enum: ProspectFailStatusEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );

        $stats = array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'success_status',
                enum: ProspectSuccessStatusEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );


        organisation()->crmStats()->update($stats);
    }
}
