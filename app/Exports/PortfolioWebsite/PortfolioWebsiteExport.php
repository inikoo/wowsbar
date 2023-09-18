<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\PortfolioWebsite;

use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PortfolioWebsiteExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|PortfolioWebsite|Builder
    {
        return PortfolioWebsite::query();
    }

    /** @var PortfolioWebsite $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->slug,
            $row->domain,
            $row->name
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Slug',
            'Domain',
            'Name'
        ];
    }
}
