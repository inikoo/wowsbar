<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Http\Resources\CRM\CustomerResource;
use App\InertiaTable\InertiaTable;
use App\Models\Assets\Language;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetCustomerOptions extends InertiaAction
{
    use AsObject;

    public function handle($customers): array
    {
        $selectOptions = [];
        /** @var Customer $customer */
        foreach ($customers as $customer) {
            $selectOptions[$customer->id] =
                [
                    'code' => $customer->slug,
                    'id'   => $customer->id,
                    'name' => $customer->name,
                ];
        }

        return $selectOptions;
    }
}
