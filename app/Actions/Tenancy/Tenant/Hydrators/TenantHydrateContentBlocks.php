<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant\Hydrators;

use App\Enums\Portfolio\ContentBlock\ContentBlockStateEnum;
use App\Models\Tenancy\Tenant;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class TenantHydrateContentBlocks implements ShouldBeUnique
{
    use AsAction;
    use HasTenantHydrate;

    public function handle(Tenant $tenant): void
    {
        $stats = [
            'number_banners' => $tenant->contentBlocks()->count()
        ];

        foreach (ContentBlockStateEnum::cases() as $state) {
            $stats['number_banners_' . $state->snake()] = $tenant->contentBlocks->where('state', $state->value)->count();
        }

        $tenant->contentBlockStats->update($stats);
    }
}
