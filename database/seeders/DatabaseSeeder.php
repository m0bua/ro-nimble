<?php

namespace Database\Seeders;

use App\Models\Eloquent\{
    Bonus,
    Category,
    CategoryOption,
    CategoryOptionTranslation,
    CategoryTranslation,
    FilterAutoranking,
    Goods,
    GoodsCarInfo,
    GoodsComment,
    GoodsLabel,
    GoodsOptionBoolean,
    GoodsOptionNumber,
    GoodsOptionPlural,
    GoodsPaymentMethod,
    GoodsTranslation,
    Label,
    LabelTranslation,
    Option,
    OptionSetting,
    OptionSettingTranslation,
    OptionTranslation,
    OptionValue,
    OptionValueCategoryRelation,
    OptionValueRelation,
    OptionValueTranslation,
    PaymentMethod,
    PaymentMethodsTerm,
    PaymentMethodTranslation,
    PaymentParentMethod,
    PaymentParentMethodTranslation,
    PrecountOptionSetting,
    PrecountOptionSlider,
    Producer,
    PromotionConstructor,
    PromotionGoodsConstructor,
    PromotionGroupConstructor,
    Series,
    SeriesTranslation
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Клас для наповнення БД з дампа в json файлах
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $params = func_get_args();
        $path = $params[0];
        $tables = $params[1];
        $output = $this->command->getOutput();

        foreach ([
            Bonus::class,
            Category::class,
            CategoryOption::class,
            CategoryOptionTranslation::class,
            CategoryTranslation::class,
//             FilterAutoranking::class,
            Goods::class,
            GoodsCarInfo::class,
            GoodsComment::class,
            GoodsOptionBoolean::class,
            GoodsOptionNumber::class,
            GoodsOptionPlural::class,
            GoodsTranslation::class,
            Label::class,
            LabelTranslation::class,
            Option::class,
            OptionSetting::class,
            OptionSettingTranslation::class,
            OptionTranslation::class,
            OptionValue::class,
            OptionValueRelation::class,
            OptionValueTranslation::class,
            PaymentMethod::class,
            PaymentMethodsTerm::class,
            PaymentMethodTranslation::class,
            PaymentParentMethod::class,
            PaymentParentMethodTranslation::class,
            PrecountOptionSetting::class,
            PrecountOptionSlider::class,
            Producer::class,
            PromotionConstructor::class,
            PromotionGoodsConstructor::class,
            PromotionGroupConstructor::class,
            Series::class,
            SeriesTranslation::class,
            GoodsPaymentMethod::class,
            GoodsLabel::class,
            OptionValueCategoryRelation::class,
        ] as $model) {
            $startTime = microtime(true);
            $obj = $model::make();

            $files = [$obj->getTable()];
            if (!empty($tables) && !\in_array($files[0], $tables)) {
                continue;
            }
            if (!File::exists("$path$files[0].json")) {
                $output->writeln("<fg=red>$path$files[0].json not found.</>");
            }
            $obj->truncate();
            $output->writeln("<fg=yellow>Seeding:</> $files[0]");
            if (File::exists("$path$files[0]_1.json")) {
                $files[] = "$files[0]_1";
            }
            foreach ($files as $file) {
                $fileDesc = \fopen("$path$file.json",'r');
                while(!\feof($fileDesc)){
                    $line = \trim(\fgets($fileDesc), "[]\n,");
                    if ($line === '') {
                        continue;
                    }
                    $data = \json_decode("[$line]", true);
                    foreach ($data as $item) {
                        foreach ($item as $column => $value) {
                            if (!\is_bool($value)) {
                                continue;
                            }
                            $item[$column] = (string)(int) $value;
                        }
                        $model::query()->insert($item);
                    }
                }
                fclose($fileDesc);
            }

            $runTime = round(microtime(true) - $startTime, 2);
            $output->writeln("<fg=green>Seeded:</>  $files[0] <fg=green>($runTime sec)</>");
        }
    }
}
