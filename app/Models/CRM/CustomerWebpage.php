<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:13:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use App\Models\Traits\HasHistory;
use App\Models\Traits\IsWebpagePortfolio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;

/**
 * App\Models\Portfolio\PortfolioWebpage
 *
 * @property int $id
 * @property string $slug
 * @property int|null $customer_id
 * @property int|null $portfolio_website_id
 * @property string $title
 * @property string $url
 * @property array $data
 * @property array $layout
 * @property string $status
 * @property string|null $message
 * @property string $source_slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\CRM\Customer|null $customer
 * @property-read \App\Models\CRM\CustomerWebsite|null $customerWebsite
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereSourceSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerWebpage whereUrl($value)
 * @mixin \Eloquent
 */
class CustomerWebpage extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use HasHistory;
    use IsWebpagePortfolio;

    protected $table = 'portfolio_webpages';

    protected $casts = [
        'data'               => 'array',
        'layout'             => 'array',
    ];

    protected $attributes = [
        'layout'             => '{}',
        'data'               => '{}'
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio'
        ];
    }

    public function customerWebsite(): BelongsTo
    {
        return $this->belongsTo(CustomerWebsite::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }



}
