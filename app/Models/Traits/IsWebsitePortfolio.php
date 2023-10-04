<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 03 Oct 2023 17:24:22 Malaysia Time, Plane KL-Bali, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;

use App\Actions\Utils\Abbreviate;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\BannerPortfolioWebsite;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\SlugOptions;

trait IsWebsitePortfolio
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Abbreviate::run(string:$this->name);
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16);
    }

    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Banner::class)->using(BannerPortfolioWebsite::class)
            ->withTimestamps();
    }
}
