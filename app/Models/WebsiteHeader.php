<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebsiteHeader
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteHeader whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WebsiteHeader extends Model
{
    use HasFactory;
}
