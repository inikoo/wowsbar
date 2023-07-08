<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:32:21 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use App\Models\Accounting\PaymentServiceProvider;
use App\Models\Assets\Currency;
use App\Models\Central\Domain;
use App\Models\Central\CentralMedia;
use App\Models\Inventory\Stock;
use App\Models\Media\Media;
use App\Models\Procurement\Agent;
use App\Models\Procurement\AgentTenant;
use App\Models\Procurement\Supplier;
use App\Models\Procurement\SupplierProduct;
use App\Models\Procurement\SupplierProductTenant;
use App\Models\Procurement\SupplierTenant;
use App\Models\SysAdmin\SysUser;
use App\Models\TenantWebStats;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Spatie\Multitenancy\TenantCollection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Tenant extends SpatieTenant implements HasMedia
{
    use HasFactory;
    use HasSlug;
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

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function stats(): HasOne
    {
        return $this->hasOne(TenantStats::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }


    public function logo(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'logo_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(256)
                    ->height(256);
            });
    }
}
