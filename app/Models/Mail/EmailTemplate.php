<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 19:32:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mail\EmailTemplate
 *
 * @property int $id
 * @property string $title
 * @property string $parent_type
 * @property int $parent_id
 * @property mixed $data
 * @property mixed $compiled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCompiled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereParentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $casts = [
        'compiled_layout' => 'array',
        'data'            => 'array',
        'state'           => EmailTemplateStateEnum::class
    ];
}
