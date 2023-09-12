<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:18:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use App\Enums\Helpers\SerialReference\SerialReferenceModelEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helpers\SerialReference
 *
 * @property int $id
 * @property string $container_type
 * @property int $container_id
 * @property SerialReferenceModelEnum $model
 * @property int $serial
 * @property string $format
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereContainerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereContainerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SerialReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SerialReference extends Model
{
    protected $casts = [
        'data'  => 'array',
        'model' => SerialReferenceModelEnum::class
    ];

    protected $attributes = [
        'data' => '{}',

    ];

    protected $guarded = [];

}
