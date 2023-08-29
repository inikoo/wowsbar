<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\PortfolioWebsite;

use App\Models\Portfolio\Banner;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BannersExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Banner|Builder
    {
        return Banner::query();
    }

    /** @var \App\Models\Portfolio\Banner $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->slug,
            $row->name,
            $row->state,
            $row->live_at,
            $row->data
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Slug',
            'Name',
            'State',
            'Live At',
            'Data'
        ];
    }
}
