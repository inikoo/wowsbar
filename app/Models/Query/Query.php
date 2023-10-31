<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $model_type
 * @property array $base
 * @property array $filters
 * @property mixed $created_at
 * @property mixed $updated_at
 * @mixin \Eloquent
 */

class Query extends Model
{
    use HasFactory;

    protected $guarded = [];
}
