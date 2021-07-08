<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class ChangeOptionSettingUkProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionSetting
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * ChangeOptionSettingUkProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
