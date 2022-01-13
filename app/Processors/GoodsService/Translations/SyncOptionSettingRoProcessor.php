<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncOptionSettingRoProcessor extends SyncAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionSetting
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
