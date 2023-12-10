<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 03 Oct 2023 17:24:22 Malaysia Time, Plane KL-Bali, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;

use App\Actions\Utils\Abbreviate;
use App\Models\SysAdmin\Division;
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

                $name=preg_replace('/\./', ' ', $this->name);
                return Abbreviate::run(string:$name);
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

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(Division::class, 'division_portfolio_websites', 'portfolio_website_id')
            ->withPivot('interest')->withTimestamps();
    }
}
