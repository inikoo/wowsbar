<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 10:51:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Models\Organisation\Organisation;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateProducts implements ShouldBeUnique
{
    use AsAction;


    public function handle(): void
    {
        $organisation=organisation();

        $stats            = [
            'number_products' => $organisation->products()->count(),
        ];

        $organisation->catalogueStats->update($stats);
    }

    public function getJobUniqueId(Organisation $parameters): string
    {
        return $parameters->id;
    }
}
