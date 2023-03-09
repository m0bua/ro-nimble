<?php

namespace App\Processors\GoodsService\Regionals;

use App\Processors\FillableProcessor;

abstract class RegionalProcessor extends FillableProcessor
{
    protected string $relationField = 'country';
    protected string $getRelation = 'regionals';
    protected string $setRelation = 'setRegional';
    protected string $getProperties = 'getRegionalProperties';
}
