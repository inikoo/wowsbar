<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:33:30 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant;

use App\Actions\InertiaAction;
use App\Models\Tenancy\Tenant;
use Inertia\Inertia;
use Inertia\Response;

class ShowTenant extends InertiaAction
{
    private Tenant $tenant;

    public function asController(): void
    {
        $this->tenant = app('currentTenant');
    }


    public function htmlResponse(): Response
    {
        $this->validateAttributes();


        return Inertia::render(
            'Tenancy/Tenant',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('tenant'),
                'pageHead'    => [
                    'title' => $this->tenant->name,
                ],





            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return [];
    }
}
