<?php

namespace App\Models;

use App\Models\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class AnnouncementTemplate extends Model implements HasMedia
{
    use HasFactory;
    use HasImages;

    protected $guarded = [];
}
