<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DivisionPortfolioWebsite
 *
 * @property int $id
 * @property int $division_id
 * @property int $portfolio_website_id
 * @property string $interest
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite query()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DivisionPortfolioWebsite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DivisionPortfolioWebsite extends Model
{
    use HasFactory;

    protected $table = 'division_portfolio_websites';

    protected $guarded = [];
}
