<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Services\Eloquent\Builder;

/**
 * App\Models\Eloquent\AbstractModel
 */
abstract class AbstractModel extends Model
{
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}
