<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Snapshot;

use App\Models\Helpers\Snapshot;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SnapshotExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Snapshot|Builder
    {
        return Snapshot::query();
    }

    /** @var Snapshot $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->parent->toArray(),
            $row->state,
            $row->layout
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Parent',
            'State',
            'Layout'
        ];
    }
}
