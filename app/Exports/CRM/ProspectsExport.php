<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\CRM;

use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProspectsExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public Organisation|Shop $parent;

    public function __construct(Organisation|Shop $parent)
    {
        $this->parent = $parent;
    }

    public function query()
    {
        return $this->customQuery($this->parent);
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
            ->defaultSort('prospects.name')
            ->with('shop')
            ->allowedSorts(['name', 'email', 'phone', 'contact_website'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    /** @var Prospect $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->phone,
            $row->contact_website,
            $row->tags->pluck('name')
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Contact Website',
            'Tags'
        ];
    }
}
