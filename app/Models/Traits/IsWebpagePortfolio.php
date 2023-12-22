<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 03 Oct 2023 17:24:22 Malaysia Time, Plane KL-Bali, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;

use App\Actions\Utils\Abbreviate;
use Spatie\Sluggable\SlugOptions;

trait IsWebpagePortfolio
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {

                return Abbreviate::run(string:$this->title, max_length:16);
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16);
    }



}
