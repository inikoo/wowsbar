<?php

namespace App\Models;

use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteFooter extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }
}
