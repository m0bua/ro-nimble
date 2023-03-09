<?php

namespace App\Processors\GoodsService\Translations;

use App\Processors\FillableProcessor;

abstract class TranslationProcessor extends FillableProcessor
{
    protected string $relationField = 'lang';
    protected string $getRelation = 'translations';
    protected string $setRelation = 'setTranslation';
    protected string $getProperties = 'getTranslatableProperties';
}
