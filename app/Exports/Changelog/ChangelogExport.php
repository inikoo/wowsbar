<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Changelog;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use OwenIt\Auditing\Models\Audit;

class ChangelogExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Audit|Builder
    {
        return Audit::query();
    }

    /** @var Audit $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->ip_address,
            $row->user_id,
            $row->url,
            $row->old_values,
            $row->new_values,
            $row->event,
            $row->auditable_type,
            $row->created_at
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'IP Address',
            'User',
            'URL',
            'Old Values',
            'New Values',
            'Action',
            'Module',
            'Date & Time'
        ];
    }
}
