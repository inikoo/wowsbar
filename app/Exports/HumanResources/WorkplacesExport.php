<?php

namespace App\Exports\HumanResources;

use App\Models\HumanResources\Workplace;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkplacesExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Workplace|Builder
    {
        return Workplace::query();
    }

    /** @var Workplace $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->slug,
            $row->name,
            $row->type,
            $row->timezone->name,
            $row->location
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Slug',
            'Name',
            'Type',
            'Timezone',
            'Location'
        ];
    }
}
