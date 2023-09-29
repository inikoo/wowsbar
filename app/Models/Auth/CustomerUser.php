<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Auth\CustomerUser
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read \App\Models\Auth\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUserId($value)
 * @mixin \Eloquent
 */
class CustomerUser extends Pivot
{
    use HasUniversalSearch;
    public $incrementing = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
