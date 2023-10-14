<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 10:23:59 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;

use App\Actions\Utils\Abbreviate;
use Spatie\Sluggable\SlugOptions;

trait IsSocialAccount
{
    use HasHistory;
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Abbreviate::run($this->platform->value).' '.$this->username;
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(12);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
