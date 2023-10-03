<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Portfolio\PortfolioWebpage
 *
 * @property int $id
 * @property int|null $portfolio_website_id
 * @property string $title
 * @property string $url
 * @property string $layout
 * @property string $status
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereUrl($value)
 * @mixin \Eloquent
 */
class PortfolioWebpage extends Model
{
    use HasFactory;

    protected $guarded = [];
}
