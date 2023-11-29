<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:10:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use App\Actions\Utils\Abbreviate;
use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Models\Assets\Currency;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\User;
use App\Models\Helpers\Snapshot;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Media\Media;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolios\CustomerSocialAccount;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Web\Website;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

/**
 * App\Models\CRM\Customer
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $reference customer public id
 * @property string|null $name
 * @property string|null $contact_name
 * @property string|null $company_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property string|null $contact_website
 * @property array $location
 * @property CustomerStatusEnum $status
 * @property CustomerStateEnum $state
 * @property CustomerTradeStateEnum $trade_state number of invoices
 * @property array $data
 * @property int $shop_id
 * @property int|null $website_id
 * @property int $language_id
 * @property int $timezone_id
 * @property int|null $image_id
 * @property string $ulid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property string|null $prospects_sender_email_address
 * @property string|null $prospects_sender_email_address_validated_at
 * @property-read Collection<int, \App\Models\CRM\Appointment> $appointment
 * @property-read int|null $appointment_count
 * @property-read Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Banner> $banners
 * @property-read int|null $banners_count
 * @property-read Currency $currency
 * @property-read Collection<int, CustomerUser> $customerUsers
 * @property-read int|null $customer_users_count
 * @property-read Collection<int, CustomerWebsite> $customerWebsites
 * @property-read int|null $customer_websites_count
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\CRM\CustomerPortfolioStats|null $portfolioStats
 * @property-read Collection<int, PortfolioWebsite> $portfolioWebsites
 * @property-read int|null $portfolio_websites_count
 * @property Collection<int, \App\Models\Helpers\Tag> $tags
 * @property-read Shop $shop
 * @property-read Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read Collection<int, CustomerSocialAccount> $socialAccounts
 * @property-read int|null $social_accounts_count
 * @property-read \App\Models\CRM\CustomerStats|null $stats
 * @property-read int|null $tags_count
 * @property-read UniversalSearch|null $universalSearch
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read Website|null $website
 * @method static Builder|Customer dProspects()
 * @method static \Database\Factories\CRM\CustomerFactory factory($count = null, $state = [])
 * @method static Builder|Customer newModelQuery()
 * @method static Builder|Customer newQuery()
 * @method static Builder|Customer onlyTrashed()
 * @method static Builder|Customer query()
 * @method static Builder|Customer whereCompanyName($value)
 * @method static Builder|Customer whereContactName($value)
 * @method static Builder|Customer whereContactWebsite($value)
 * @method static Builder|Customer whereCreatedAt($value)
 * @method static Builder|Customer whereData($value)
 * @method static Builder|Customer whereDeleteComment($value)
 * @method static Builder|Customer whereDeletedAt($value)
 * @method static Builder|Customer whereEmail($value)
 * @method static Builder|Customer whereId($value)
 * @method static Builder|Customer whereIdentityDocumentNumber($value)
 * @method static Builder|Customer whereIdentityDocumentType($value)
 * @method static Builder|Customer whereImageId($value)
 * @method static Builder|Customer whereLanguageId($value)
 * @method static Builder|Customer whereLocation($value)
 * @method static Builder|Customer whereName($value)
 * @method static Builder|Customer wherePhone($value)
 * @method static Builder|Customer whereProspectsSenderEmailAddress($value)
 * @method static Builder|Customer whereProspectsSenderEmailAddressValidatedAt($value)
 * @method static Builder|Customer whereReference($value)
 * @method static Builder|Customer whereShopId($value)
 * @method static Builder|Customer whereSlug($value)
 * @method static Builder|Customer whereState($value)
 * @method static Builder|Customer whereStatus($value)
 * @method static Builder|Customer whereTimezoneId($value)
 * @method static Builder|Customer whereTradeState($value)
 * @method static Builder|Customer whereUlid($value)
 * @method static Builder|Customer whereUpdatedAt($value)
 * @method static Builder|Customer whereWebsiteId($value)
 * @method static Builder|Customer withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Customer withAllTagsOfAnyType($tags)
 * @method static Builder|Customer withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Customer withAnyTagsOfAnyType($tags)
 * @method static Builder|Customer withTrashed()
 * @method static Builder|Customer withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Customer withoutTrashed()
 * @mixin Eloquent
 */
class Customer extends Model implements HasMedia, Auditable
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasPhoto;
    use HasFactory;
    use HasHistory;
    use HasTags;

    protected $casts = [
        'data'        => 'array',
        'location'    => 'array',
        'state'       => CustomerStateEnum::class,
        'status'      => CustomerStatusEnum::class,
        'trade_state' => CustomerTradeStateEnum::class
    ];

    protected $attributes = [
        'data'     => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'crm'
        ];
    }

    protected array $auditExclude = [
        'id','slug',
        'reference',
        'website_id',
        'location'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                if(mb_strlen($this->name)>=6) {
                    return Abbreviate::run($this->name);
                } else {
                    return  $this->name;
                }
            })
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(12)
            ->doNotGenerateSlugsOnUpdate()
            ->doNotGenerateSlugsOnCreate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(
            function (Customer $customer) {
                $customer->name = $customer->company_name == '' ? $customer->contact_name : $customer->company_name;
            }
        );

        static::updated(function (Customer $customer) {
            if ($customer->wasChanged(['contact_name', 'company_name'])) {
                $customer->updateQuietly(
                    [
                        'name'=> $customer->company_name == '' ? $customer->contact_name : $customer->company_name
                    ]
                );
            }
        });
    }

    public function stats(): HasOne
    {
        return $this->hasOne(CustomerStats::class);
    }

    public function portfolioStats(): HasOne
    {
        return $this->hasOne(CustomerPortfolioStats::class);
    }


    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, CustomerUser::class);
    }

    public function customerUsers(): HasMany
    {
        return $this->hasMany(CustomerUser::class);
    }


    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    public function logo(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'logo_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
    }

    public function portfolioWebsites(): HasMany
    {
        return $this->hasMany(PortfolioWebsite::class);
    }

    public function customerWebsites(): HasMany
    {
        return $this->hasMany(CustomerWebsite::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopedProspects(): MorphMany
    {
        return $this->morphMany(Prospect::class, 'parent');
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(CustomerSocialAccount::class);
    }
}
