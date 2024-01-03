<?php

namespace App\Models;

use App\Actions\Utils\Abbreviate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 *
 * @property int $id
 * @property string $slug
 * @property string $label
 *
 */

class ShipperAccount extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];
    protected $casts = [
        'credentials' => 'array',
        'data'        => 'array'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(12)
            ->doNotGenerateSlugsOnUpdate();
    }
}
