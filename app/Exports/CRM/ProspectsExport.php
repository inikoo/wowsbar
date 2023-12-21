<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\CRM;

use App\Models\Leads\Prospect;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProspectsExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public mixed $result;

    public function __construct(mixed $result)
    {
        $this->result = $result;
    }

    public function query()
    {
        return $this->result;
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
