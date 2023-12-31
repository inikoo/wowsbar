<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\SysAdmin;

use App\Models\Accounting\PaymentServiceProvider;
use App\Models\Assets\Currency;
use App\Models\Assets\Timezone;
use App\Models\Auth\OrganisationUser;
use App\Models\Catalogue\Product;
use App\Models\Catalogue\ProductCategory;
use App\Models\Mail\EmailTemplate;
use App\Models\Market\Shop;
use App\Models\Traits\HasLogo;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
 * @property int $currency_id customer accounting currency
 * @property int|null $logo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SysAdmin\OrganisationCatalogueStats|null $catalogueStats
 * @property-read \App\Models\SysAdmin\OrganisationCrmStats|null $crmStats
 * @property-read Currency $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductCategory> $departments
 * @property-read int|null $departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EmailTemplate> $emailTemplates
 * @property-read int|null $email_templates_count
 * @property-read \App\Models\SysAdmin\OrganisationHumanResourcesStats|null $humanResourcesStats
 * @property-read \App\Models\Media\Media|null $logo
 * @property-read \App\Models\SysAdmin\OrganisationMailStats|null $mailStats
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SysAdmin\OrganisationPortfoliosStats|null $portfoliosStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Shop> $shops
 * @property-read int|null $shops_count
 * @property-read \App\Models\SysAdmin\OrganisationStats|null $stats
 * @property-read \App\Models\SysAdmin\OrganisationTaskStats|null $taskStats
 * @property-read Timezone $timezone
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganisationUser> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Website> $websites
 * @property-read int|null $websites_count
 * @method static \Database\Factories\SysAdmin\OrganisationFactory factory($count = null, $state = [])
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
    use HasFactory;
    use HasLogo;

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

    public function crmStats(): HasOne
    {
        return $this->hasOne(OrganisationCrmStats::class);
    }

    public function catalogueStats(): HasOne
    {
        return $this->hasOne(OrganisationCatalogueStats::class);
    }

    public function portfoliosStats(): HasOne
    {
        return $this->hasOne(OrganisationPortfoliosStats::class);
    }

    public function taskStats(): HasOne
    {
        return $this->hasOne(OrganisationTaskStats::class);
    }

    public function mailStats(): HasOne
    {
        return $this->hasOne(OrganisationMailStats::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(OrganisationUser::class);
    }

    public function accountsServiceProvider(): PaymentServiceProvider
    {
        return PaymentServiceProvider::where('code', 'accounts')->first();
    }

    public function departments(): MorphMany
    {
        return $this->morphMany(ProductCategory::class, 'parent');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'parent');
    }

    public function emailTemplates(): MorphMany
    {
        return $this->morphMany(EmailTemplate::class, 'parent');
    }

}
