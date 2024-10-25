<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\AnnouncementTemplate\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\AnnouncementTemplatesResource;
use App\InertiaTable\InertiaTable;
use App\Models\AnnouncementTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\QueryBuilder;

class IndexAnnouncementTemplates extends InertiaAction
{
    public function handle($prefix = null): Collection|array
    {
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(AnnouncementTemplate::class);

        return $queryBuilder->get();
    }

    public function jsonResponse(Collection $collection): AnonymousResourceCollection
    {
        return AnnouncementTemplatesResource::collection($collection);
    }

    public function asController(ActionRequest $request): array|Collection
    {
        $this->initialisation($request);

        return $this->handle();
    }
}
