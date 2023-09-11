<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\StockImages;

use App\Helpers\ImgProxy\Image;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockImagesExport implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings
{
    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|Media|Builder
    {
        return Media::query();
    }

    /** @var Media $row */
    public function map($row): array
    {
        $imageThumbnail = (new Image())->make($row->getImgProxyFilename(), $row->is_animated)->resize(0, 48);

        return [
            $row->id,
            $row->name,
            json_encode($imageThumbnail),
            $row->size,
            $row->created_at
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Image',
            'Size',
            'Uploaded At'
        ];
    }
}
