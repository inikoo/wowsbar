<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use App\Models\Accounting\PaymentServiceProvider;
use App\Models\Assets\Currency;
use App\Models\Organisation\Market\Shop;
use App\Models\Traits\HasOrganisationUniversalSearch;
use App\Models\Organisation\Web\Website;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Organisation\Organisation
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
 * @property int|null $logo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Currency $currency
 * @property-read \App\Models\Organisation\OrganisationHumanResourcesStats|null $humanResourcesStats
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read Shop|null $shop
 * @property-read \App\Models\Organisation\OrganisationStats|null $stats
 * @property-read \App\Models\Search\OrganisationUniversalSearch|null $universalSearch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation\OrganisationUser> $users
 * @property-read int|null $users_count
 * @property-read Website|null $website
 * @method static \Database\Factories\Organisation\OrganisationFactory factory($count = null, $state = [])
 * @method static Builder|Organisation newModelQuery()
 * @method static Builder|Organisation newQuery()
 * @method static Builder|Organisation query()
 * @method static Builder|Organisation whereCode($value)
 * @method static Builder|Organisation whereCountryId($value)
 * @method static Builder|Organisation whereCreatedAt($value)
 * @method static Builder|Organisation whereCurrencyId($value)
 * @method static Builder|Organisation whereData($value)
 * @method static Builder|Organisation whereId($value)
 * @method static Builder|Organisation whereLanguageId($value)
 * @method static Builder|Organisation whereLogoId($value)
 * @method static Builder|Organisation whereName($value)
 * @method static Builder|Organisation whereSettings($value)
 * @method static Builder|Organisation whereTimezoneId($value)
 * @method static Builder|Organisation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Organisation extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasOrganisationUniversalSearch;
    use HasFactory;

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
        return $this->hasOne(OrganisationStats::class);
    }

    public function humanResourcesStats(): HasOne
    {
        return $this->hasOne(OrganisationHumanResourcesStats::class);
    }

    public function website(): HasOne
    {
        return $this->hasOne(Website::class);
    }

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(OrganisationUser::class);
    }

    public function accountsServiceProvider(): PaymentServiceProvider
    {
        return PaymentServiceProvider::where('data->service-code', 'accounts')->first();
    }


}
