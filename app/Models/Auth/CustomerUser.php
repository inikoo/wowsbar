<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use App\Models\CRM\Customer;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Auth\CustomerUser
 *
 * @property int $id
 * @property bool $is_root
 * @property bool $status
 * @property int $customer_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read \App\Models\Auth\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereIsRoot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUserId($value)
 * @mixin \Eloquent
 */
class CustomerUser extends Model
{
    use HasUniversalSearch;
    use HasRoles;

    protected $table='customer_user';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function guardName(): string
    {
        return 'customer';
    }

}
