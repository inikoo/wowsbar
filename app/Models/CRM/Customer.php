<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:21:10 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Models\Search\UniversalSearch;
use App\Models\Tenancy\Tenant;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


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
 * @property int|null $tenant_id
 * @property int|null $image_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, \App\Models\CRM\PublicUser> $publicUsers
 * @property-read int|null $public_users_count
 * @property-read \App\Models\CRM\CustomerStats|null $stats
 * @property-read Tenant|null $tenant
 * @property-read UniversalSearch|null $universalSearch
 * @method static Builder|Customer newModelQuery()
 * @method static Builder|Customer newQuery()
 * @method static Builder|Customer onlyTrashed()
 * @method static Builder|Customer query()
 * @method static Builder|Customer whereCompanyName($value)
 * @method static Builder|Customer whereContactName($value)
 * @method static Builder|Customer whereContactWebsite($value)
 * @method static Builder|Customer whereCreatedAt($value)
 * @method static Builder|Customer whereData($value)
 * @method static Builder|Customer whereDeletedAt($value)
 * @method static Builder|Customer whereEmail($value)
 * @method static Builder|Customer whereId($value)
 * @method static Builder|Customer whereIdentityDocumentNumber($value)
 * @method static Builder|Customer whereIdentityDocumentType($value)
 * @method static Builder|Customer whereImageId($value)
 * @method static Builder|Customer whereLocation($value)
 * @method static Builder|Customer whereName($value)
 * @method static Builder|Customer wherePhone($value)
 * @method static Builder|Customer whereReference($value)
 * @method static Builder|Customer whereSlug($value)
 * @method static Builder|Customer whereState($value)
 * @method static Builder|Customer whereStatus($value)
 * @method static Builder|Customer whereTenantId($value)
 * @method static Builder|Customer whereTradeState($value)
 * @method static Builder|Customer whereUpdatedAt($value)
 * @method static Builder|Customer withTrashed()
 * @method static Builder|Customer withoutTrashed()
 * @mixin Eloquent
 */
class Customer extends Model implements HasMedia
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasPhoto;
    use HasFactory;

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

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('reference')
            ->saveSlugsTo('slug')
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
                $customer->name = $customer->company_name == '' ? $customer->contact_name : $customer->company_name;
            }
        });
    }

    public function stats(): HasOne
    {
        return $this->hasOne(CustomerStats::class);
    }

    public function publicUsers(): HasMany
    {
        return $this->hasMany(PublicUser::class);
    }

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }
}
