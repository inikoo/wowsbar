<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Mar 2023 20:59:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Mail;

use App\Actions\Utils\Abbreviate;
use App\Models\Market\Shop;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Mail\Outbox
 *
 * @property int $id
 * @property int|null $mailroom_id
 * @property int|null $shop_id
 * @property string $slug
 * @property string $type
 * @property string $name
 * @property string $state
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $source_id
 * @property-read Collection<int, \App\Models\Mail\DispatchedEmail> $dispatchedEmails
 * @property-read int|null $dispatched_emails_count
 * @property-read Collection<int, \App\Models\Mail\EmailTemplate> $emailTemplates
 * @property-read int|null $email_templates_count
 * @property-read Collection<int, \App\Models\Mail\Mailshot> $mailshots
 * @property-read int|null $mailshots_count
 * @property-read Shop|null $shop
 * @property-read \App\Models\Mail\OutboxStats|null $stats
 * @method static Builder|Outbox newModelQuery()
 * @method static Builder|Outbox newQuery()
 * @method static Builder|Outbox onlyTrashed()
 * @method static Builder|Outbox query()
 * @method static Builder|Outbox whereCreatedAt($value)
 * @method static Builder|Outbox whereData($value)
 * @method static Builder|Outbox whereDeletedAt($value)
 * @method static Builder|Outbox whereId($value)
 * @method static Builder|Outbox whereMailroomId($value)
 * @method static Builder|Outbox whereName($value)
 * @method static Builder|Outbox whereShopId($value)
 * @method static Builder|Outbox whereSlug($value)
 * @method static Builder|Outbox whereSourceId($value)
 * @method static Builder|Outbox whereState($value)
 * @method static Builder|Outbox whereType($value)
 * @method static Builder|Outbox whereUpdatedAt($value)
 * @method static Builder|Outbox withTrashed()
 * @method static Builder|Outbox withoutTrashed()
 * @mixin Eloquent
 */
class Outbox extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasFactory;

    protected $table = 'outboxes';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                if ($this->type == 'reorder-reminder') {
                    $abbreviation = 'ror';
                } else {
                    $abbreviation = Abbreviate::run($this->type);
                }

                if ($this->shop_id) {
                    $abbreviation = $abbreviation.' '.$this->shop->slug;
                }

                return $abbreviation;
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(OutboxStats::class);
    }

    public function mailshots(): HasMany
    {
        return $this->hasMany(Mailshot::class);
    }

    public function emailTemplates(): MorphMany
    {
        return $this->morphMany(EmailTemplate::class, 'parent');
    }


    public function dispatchedEmails(): HasMany
    {
        return $this->hasMany(DispatchedEmail::class);
    }
}
