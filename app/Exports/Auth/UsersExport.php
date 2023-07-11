<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|User|Builder
    {
        return User::query();
    }

    /** @var User $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->username,
            $row->email,
            $row->contact_name,
            $row->about,
            $row->status
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Username',
            'Email',
            'Contact Name',
            'About',
            'Status',
        ];
    }
}
