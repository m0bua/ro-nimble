<?php

namespace Tests\Unit\Processors\Processor;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestModel extends Model
{
    use HasFillable;

    protected $fillable = [
        'field1',
        'field2',
        'field3',
        'field4',
    ];
}
