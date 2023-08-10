<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait IsMedia
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->slugsShouldBeNoLongerThan(24)
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }


    public function getRouteKeyName(): string
    {
        return 'slug';
    }



    public function getLocalImgProxyFilename(): string
    {
        $rootPath='/'.config('app.name').Str::after(Storage::disk($this->disk)->path(''), storage_path());

        $prefix   =config('media-library.prefix', '');
        $mediaPath=$prefix ? $prefix.'/' : '';
        $mediaPath.=$this->id.'/'.$this->file_name;

        return 'local://'.$rootPath.$mediaPath;
    }

}
