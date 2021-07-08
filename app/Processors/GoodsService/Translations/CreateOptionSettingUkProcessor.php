<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class CreateOptionSettingUkProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionSetting
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * CreateOptionSettingUkProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
