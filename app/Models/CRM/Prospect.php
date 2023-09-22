<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:10:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use App\Actions\Utils\Abbreviate;
use App\Actions\Utils\ReadableRandomStringGenerator;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Market\Shop;
use App\Models\Search\UniversalSearch;
use App\Models\Traits\HasUniversalSearch;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\CRM\Prospect
 *
 * @property int $id
 * @property string $slug
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
 * @property-read \App\Models\CRM\Customer|null $customer
 * @property-read UniversalSearch|null $universalSearch
 * @method static \Database\Factories\CRM\ProspectFactory factory($count = null, $state = [])
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
 * @method static Builder|Prospect whereDeletedAt($value)
 * @method static Builder|Prospect whereEmail($value)
 * @method static Builder|Prospect whereId($value)
 * @method static Builder|Prospect whereIdentityDocumentNumber($value)
 * @method static Builder|Prospect whereIdentityDocumentType($value)
 * @method static Builder|Prospect whereLocation($value)
 * @method static Builder|Prospect whereName($value)
 * @method static Builder|Prospect wherePhone($value)
 * @method static Builder|Prospect wherePortfolioWebsiteId($value)
 * @method static Builder|Prospect whereScopeId($value)
 * @method static Builder|Prospect whereScopeType($value)
 * @method static Builder|Prospect whereShopId($value)
 * @method static Builder|Prospect whereSlug($value)
 * @method static Builder|Prospect whereState($value)
 * @method static Builder|Prospect whereUpdatedAt($value)
 * @method static Builder|Prospect withTrashed()
 * @method static Builder|Prospect withoutTrashed()
 * @mixin Eloquent
 */
class Prospect extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasUniversalSearch;
    use HasFactory;

    protected $casts = [
        'data'     => 'array',
        'location' => 'array',
        'state'    => ProspectStateEnum::class
    ];

    protected $attributes = [
        'data'     => '{}',
        'location' => '{}',
        'state'    => 'registered'
    ];


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
