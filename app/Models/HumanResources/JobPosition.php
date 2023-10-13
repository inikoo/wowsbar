<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Models\Auth\Role;
use App\Models\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\JobPosition
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property string|null $department
 * @property string|null $team
 * @property array $data
 * @property int $number_employees
 * @property int $number_roles
 * @property float $number_work_time
 * @property string|null $share_work_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereNumberEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereNumberRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereNumberWorkTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereShareWorkTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JobPosition extends Model implements Auditable
{
    use HasSlug;
    use HasHistory;


    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(8);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'hr'
        ];
    }

    protected $auditExclude = [
        'share_work_time',
        'number_employees'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
}
