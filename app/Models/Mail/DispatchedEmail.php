<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 08:44:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use App\Enums\Mail\DispatchedEmailStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Mail\DispatchedEmail
 *
 * @property int $id
 * @property int $email_id
 * @property string|null $provider_message_id
 * @property DispatchedEmailStateEnum $state
 * @property string $date
 * @property string|null $sent_at
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mail\Email $email
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mail\DispatchedEmailEvent> $events
 * @property-read int|null $events_count
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereProviderMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DispatchedEmail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DispatchedEmail extends Model
{
    protected $casts = [
        'data'        => 'array',
        'state'       => DispatchedEmailStateEnum::class,
    ];

    protected $attributes = [
        'data'     => '{}',
    ];

    protected $guarded = [];

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
    public function events(): HasMany
    {
        return $this->hasMany(DispatchedEmailEvent::class);

    }

}
