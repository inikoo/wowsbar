<?php

namespace App\Models;

use App\Models\Media\Media;
use App\Models\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class AnnouncementTemplate extends Model implements HasMedia
{
    use HasFactory;
    use HasImages;

    protected $guarded = [];

    public function screenshot(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'screenshot_id');
    }
}
