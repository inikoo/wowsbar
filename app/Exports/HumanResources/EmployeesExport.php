<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\HumanResources;

use App\Models\HumanResources\Employee;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Employee|Builder
    {
        return Employee::query();
    }

    /** @var Employee $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->slug,
            $row->contact_name,
            $row->work_email,
            $row->phone,
            $row->job_title,
            $row->type,
            $row->state,
            $row->emergency_contact,
            $row->salary,
            $row->working_hours,
            $row->week_working_hours
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Slug',
            'Contact Name',
            'Work Email',
            'Phone',
            'Job Title',
            'Type',
            'State',
            'Emergency Contact',
            'Salary',
            'Working Hours',
            'Week Working Hours'
        ];
    }
}
