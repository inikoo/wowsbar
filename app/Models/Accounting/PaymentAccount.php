<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:09:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use App\Models\Traits\HasHistory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Payments\PaymentAccount
 *
 * @property int $id
 * @property int $payment_service_provider_id
 * @property string $code
 * @property string $slug
 * @property string $name
 * @property array $data
 * @property string|null $last_used_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Accounting\PaymentServiceProvider $paymentServiceProvider
 * @property-read Collection<int, \App\Models\Accounting\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Accounting\PaymentAccountStats|null $stats
 * @method static Builder|PaymentAccount newModelQuery()
 * @method static Builder|PaymentAccount newQuery()
 * @method static Builder|PaymentAccount onlyTrashed()
 * @method static Builder|PaymentAccount query()
 * @method static Builder|PaymentAccount whereCode($value)
 * @method static Builder|PaymentAccount whereCreatedAt($value)
 * @method static Builder|PaymentAccount whereData($value)
 * @method static Builder|PaymentAccount whereDeleteComment($value)
 * @method static Builder|PaymentAccount whereDeletedAt($value)
 * @method static Builder|PaymentAccount whereId($value)
 * @method static Builder|PaymentAccount whereLastUsedAt($value)
 * @method static Builder|PaymentAccount whereName($value)
 * @method static Builder|PaymentAccount wherePaymentServiceProviderId($value)
 * @method static Builder|PaymentAccount whereSlug($value)
 * @method static Builder|PaymentAccount whereUpdatedAt($value)
 * @method static Builder|PaymentAccount withTrashed()
 * @method static Builder|PaymentAccount withoutTrashed()
 * @mixin Eloquent
 */
class PaymentAccount extends Model implements Auditable
{
    use SoftDeletes;
    use HasSlug;
    use HasFactory;
    use HasHistory;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'accounting'
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function paymentServiceProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentServiceProvider::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(PaymentAccountStats::class);
    }
}
