<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\CRM\Prospect\ProspectBounceStatusEnum;
use App\Enums\CRM\Prospect\ProspectContactStateEnum;
use App\Enums\CRM\Prospect\ProspectOutcomeStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateProspects
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_prospects'                 => Prospect::where('scope_type', 'Shop')->count(),
            'number_prospects_dont_contact_me' => Prospect::where('scope_type', 'Shop')->where('dont_contact_me', true)->count(),
            'number_prospects_contacted'       => Prospect::where('scope_type', 'Shop')->where('contact_state', ProspectContactStateEnum::CONTACTED)->count(),
            'number_prospects_not_contacted'   => Prospect::where('scope_type', 'Shop')->where('contact_state', ProspectContactStateEnum::NO_CONTACTED)->count(),

        ];

        $stats=array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'outcome_status',
                enum: ProspectOutcomeStatusEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );

        $stats=array_merge(
            $stats,
            $this->getEnumStats(
                model: 'prospects',
                field: 'bounce_status',
                enum: ProspectBounceStatusEnum::class,
                models: Prospect::class,
                where: function ($q) {
                    $q->where('scope_type', 'Shop');
                }
            )
        );

        $stats=array_merge(
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

        $stats=array_merge(
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
        organisation()->crmStats()->update($stats);
    }
}
