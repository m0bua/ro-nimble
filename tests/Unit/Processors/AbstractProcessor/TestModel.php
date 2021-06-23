<?php

namespace Tests\Unit\Processors\AbstractProcessor;

use App\Traits\Eloquent\HasFillable;
use Illuminate\Database\Eloquent\Model;

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
