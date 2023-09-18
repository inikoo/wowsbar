<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebsiteUploadRecord
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeUploadRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeUploadRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeUploadRecord query()
 * @mixin \Eloquent
 */

class EmployeeUploadRecord extends Model
{
    use HasFactory;

    protected $guarded = [];
}
