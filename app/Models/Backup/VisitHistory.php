<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Reviewed: Tue, 11 Jul 2023 12:11:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Backup;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Backup\VisitHistory
 *
 * @property int $id
 * @property string $index
 * @property string $type
 * @property bool $synced
 * @property array $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|VisitHistory newModelQuery()
 * @method static Builder|VisitHistory newQuery()
 * @method static Builder|VisitHistory query()
 * @method static Builder|VisitHistory whereBody($value)
 * @method static Builder|VisitHistory whereCreatedAt($value)
 * @method static Builder|VisitHistory whereId($value)
 * @method static Builder|VisitHistory whereIndex($value)
 * @method static Builder|VisitHistory whereSynced($value)
 * @method static Builder|VisitHistory whereType($value)
 * @method static Builder|VisitHistory whereUpdatedAt($value)
 * @mixin Eloquent
 */

class VisitHistory extends Model
{
    protected $connection = 'backup';

    protected $casts = [
        'body'   => 'array',
    ];

    protected $attributes = [
        'body' => '{}',
    ];

    protected $guarded = [];
}
