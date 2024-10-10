<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        "container_properties" => "array",	
        "fields	" => "array",	
    ];
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('code');
    }
}
