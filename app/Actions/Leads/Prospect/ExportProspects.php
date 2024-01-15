<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Traits\WithExportData;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Exports\CRM\ProspectsExport;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportProspects
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(array $objectData): BinaryFileResponse
    {
        $type = $objectData['type'];

        if($parent = request()->route('shop')) {
            $parent = Shop::whereSlug($parent)->firstOrFail();
        } else {
            $parent = organisation();
        }

        $result = $this->customQuery($parent);

        return $this->export(new ProspectsExport($result), 'prospects', $type);
    }


    protected function getElementGroups($parent): array
    {
        return
            [
                'state' => [
                    'label'    => __('State'),
                    'elements' => array_merge_recursive(
                        ProspectStateEnum::labels(),
                        ProspectStateEnum::count($parent)
                    ),
                    'engine'   => function ($query, $elements) {
                        $query->whereIn('prospects.state', $elements);
                    }
                ]
            ];
    }

    public function customQuery(Organisation|Shop $parent, $prefix = null)
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('prospects.name', '~*', "\y$value\y")
                    ->orWhere('prospects.email', '=', $value)
                    ->orWhere('prospects.phone', '=', $value)
                    ->orWhere('prospects.contact_website', '=', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Prospect::class);


        if (class_basename($parent) == 'Shop') {
            $query->where('shop_id', $parent->id);
        }

        foreach ($this->getElementGroups($parent) as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $query->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $query
            ->allowedFilters([$globalSearch]);
    }

    /**
     * @throws \Throwable
     */
    public function asController(ActionRequest $request): BinaryFileResponse
    {
        $this->setRawAttributes($request->all());
        $this->validateAttributes();

        return $this->handle($request->all());
    }
}
