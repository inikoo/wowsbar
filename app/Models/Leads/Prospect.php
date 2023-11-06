<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 14:23:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Leads;

use App\Actions\Utils\Abbreviate;
use App\Actions\Utils\ReadableRandomStringGenerator;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

/**
 * App\Models\Leads\Prospect
 *
 * @property int $id
 * @property string|null $slug
 * @property string $scope_type
 * @property int $scope_id
 * @property int|null $shop_id
 * @property int|null $customer_id
 * @property int|null $portfolio_website_id
 * @property string|null $name
 * @property string|null $contact_name
 * @property string|null $company_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property string|null $contact_website
 * @property array $location
 * @property ProspectStateEnum $state
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property string|null $last_contacted_at
 * @property string|null $not_interested_at
 * @property string|null $registered_at
 * @property string|null $invoiced_at
 * @property string|null $last_bounced_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Assets\Country $country
 * @property-read Customer|null $customer
 * @property-read string $formatted_address
 * @property-read Model|\Eloquent $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read Shop|null $shop
 * @property-read int|null $tags_count
 * @property-read UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Leads\ProspectFactory factory($count = null, $state = [])
 * @method static Builder|Prospect newModelQuery()
 * @method static Builder|Prospect newQuery()
 * @method static Builder|Prospect onlyTrashed()
 * @method static Builder|Prospect query()
 * @method static Builder|Prospect whereCompanyName($value)
 * @method static Builder|Prospect whereContactName($value)
 * @method static Builder|Prospect whereContactWebsite($value)
 * @method static Builder|Prospect whereCreatedAt($value)
 * @method static Builder|Prospect whereCustomerId($value)
 * @method static Builder|Prospect whereData($value)
 * @method static Builder|Prospect whereDeleteComment($value)
 * @method static Builder|Prospect whereDeletedAt($value)
 * @method static Builder|Prospect whereEmail($value)
 * @method static Builder|Prospect whereId($value)
 * @method static Builder|Prospect whereIdentityDocumentNumber($value)
 * @method static Builder|Prospect whereIdentityDocumentType($value)
 * @method static Builder|Prospect whereInvoicedAt($value)
 * @method static Builder|Prospect whereLastBouncedAt($value)
 * @method static Builder|Prospect whereLastContactedAt($value)
 * @method static Builder|Prospect whereLocation($value)
 * @method static Builder|Prospect whereName($value)
 * @method static Builder|Prospect whereNotInterestedAt($value)
 * @method static Builder|Prospect wherePhone($value)
 * @method static Builder|Prospect wherePortfolioWebsiteId($value)
 * @method static Builder|Prospect whereRegisteredAt($value)
 * @method static Builder|Prospect whereScopeId($value)
 * @method static Builder|Prospect whereScopeType($value)
 * @method static Builder|Prospect whereShopId($value)
 * @method static Builder|Prospect whereSlug($value)
 * @method static Builder|Prospect whereState($value)
 * @method static Builder|Prospect whereUpdatedAt($value)
 * @method static Builder|Prospect withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Prospect withAllTagsOfAnyType($tags)
 * @method static Builder|Prospect withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Prospect withAnyTagsOfAnyType($tags)
 * @method static Builder|Prospect withTrashed()
 * @method static Builder|Prospect withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|Prospect withoutTrashed()
 * @mixin Eloquent
 */
class Prospect extends Model implements Auditable
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;
    use HasTags;
    use HasHistory;
    use HasAddress;

    protected $casts = [
        'data'     => 'array',
        'location' => 'array',
        'state'    => ProspectStateEnum::class
    ];

    protected $attributes = [
        'data'     => '{}',
        'location' => '{}',
    ];

    public function generateTags(): array
    {
        return [
            'crm'
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (Prospect $prospect) {
                $prospect->name = $prospect->company_name == '' ? $prospect->contact_name : $prospect->company_name;
            }
        );


        static::updated(function (Prospect $prospect) {
            if ($prospect->wasChanged(['company_name', 'contact_name'])) {
                $prospect->name = $prospect->company_name == '' ? $prospect->contact_name : $prospect->company_name;
            }
        });
    }

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                $name = $this->company_name == '' ? $this->contact_name : $this->company_name;
                if ($name != '') {
                    return Abbreviate::run($name);
                }

                return ReadableRandomStringGenerator::run();
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
