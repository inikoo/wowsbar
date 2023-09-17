<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 12:01:47 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use Illuminate\Support\Arr;

trait WithEnumStats
{
    private function getEnumStats(
        string $model,
        string $field,
        $enum,
        $models
    ): array {
        $stats = [];
        $count = $models::selectRaw("$field, count(*) as total")
            ->groupBy($field)
            ->pluck('total', $field)->all();
        foreach ($enum::cases() as $case) {
            $stats["number_{$model}_{$field}_".$case->snake()] = Arr::get($count, $case->value, 0);
        }

        return $stats;
    }
}
