<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use App\Actions\Utils\Abbreviate;
use App\Models\CRM\Customer;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Auth\CustomerUser
 *
 * @property int $id
 * @property string|null $slug
 * @property bool $is_root
 * @property bool $status
 * @property int $customer_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
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
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerUser whereUserId($value)
 * @mixin \Eloquent
 */
class CustomerUser extends Model implements Auditable
{
    use HasUniversalSearch;
    use HasRoles;
    use HasSlug;
    use HasHistory;

    protected $table='customer_user';

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function generateTags(): array
    {
        return [
            'customer-sysadmin'
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return $this->user->slug.'-'.Abbreviate::run($this->customer->slug);
            })
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(24);
    }



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
