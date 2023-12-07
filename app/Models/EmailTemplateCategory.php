<?php

namespace App\Models;

use App\Models\Mail\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class EmailTemplateCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(EmailTemplate::class, EmailTemplatePivotEmailCategory::class);
    }
}
