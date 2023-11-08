<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 08:44:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use App\Enums\Mail\EmailDeliveryStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Mail\EmailDelivery
 *
 * @property int $id
 * @property int $email_id
 * @property EmailDeliveryStateEnum $state
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mail\Email|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailDelivery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailDelivery extends Model
{
    protected $casts = [
        'state'       => EmailDeliveryStateEnum::class,
    ];

    protected $guarded = [];

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
