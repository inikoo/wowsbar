<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:10:04 Malaysia Time, Pantai Lembeng,, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Landlord;

use App\Models\Assets\Currency;
use App\Models\Auth\User;
use App\Models\Tenancy\TenantStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Landlord\Landlord
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property array $data
 * @property array $settings
 * @property int $country_id
 * @property int $language_id
 * @property int $timezone_id
 * @property int $currency_id tenant accounting currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Currency $currency
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read TenantStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|Landlord newModelQuery()
 * @method static Builder|Landlord newQuery()
 * @method static Builder|Landlord onlyTrashed()
 * @method static Builder|Landlord query()
 * @method static Builder|Landlord whereCode($value)
 * @method static Builder|Landlord whereCountryId($value)
 * @method static Builder|Landlord whereCreatedAt($value)
 * @method static Builder|Landlord whereCurrencyId($value)
 * @method static Builder|Landlord whereData($value)
 * @method static Builder|Landlord whereDeletedAt($value)
 * @method static Builder|Landlord whereId($value)
 * @method static Builder|Landlord whereLanguageId($value)
 * @method static Builder|Landlord whereName($value)
 * @method static Builder|Landlord whereSettings($value)
 * @method static Builder|Landlord whereTimezoneId($value)
 * @method static Builder|Landlord whereUpdatedAt($value)
 * @method static Builder|Landlord withTrashed()
 * @method static Builder|Landlord withoutTrashed()
 * @mixin \Eloquent
 */
class Landlord extends Model implements HasMedia
{
    use InteractsWithMedia;


    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];


    public function stats(): HasOne
    {
        return $this->hasOne(LandlordStats::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
