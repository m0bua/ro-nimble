<?php

namespace App\Console\Commands\Seed;

use App\Models\Eloquent\{Bonus,
    Category,
    CategoryOption,
    CategoryOptionTranslation,
    CategoryTranslation,
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
    SeriesTranslation};
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use function collect;
use function config;

/**
 * Клас для генерації дампа частини БД в json файлах
 */
class GenerateSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:generate-seed {--category_ids=*80253} {--main_dump=false} {--goods_count=10000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates seed data from active DB';

    /**
     * Максимальна кількість рядків з БД
     *
     * @var int
     */
    private int $maxBatch = 100;

    /**
     * Максимальна кількість елементів в умові `where in()`
     *
     * @var int
     */
    private int $whereInChunk = 50;

    /**
     * @var string
     */
    private string $path = '';

    /**
     * @var Collection
     */
    private Collection $categoryOptionIds;

    /**
     * @var Collection
     */
    private Collection $goodsIds;

    /**
     * @var Collection
     */
    private Collection $producerIds;

    /**
     * @var Collection
     */
    private Collection $groupIds;

    /**
     * @var Collection
     */
    private Collection $seriesIds;

    /**
     * @var Collection
     */
    private Collection $optionIds;

    /**
     * @var Collection
     */
    private Collection $optionParentIds;

    /**
     * @var Collection
     */
    private Collection $optionValueIds;

    /**
     * @var Collection
     */
    private Collection $optionValueParentIds;

    /**
     * @var Collection
     */
    private Collection $optionSettingIds;

    /**
     * @var Collection
     */
    private Collection $constructorIds;

    /**
     * @var Collection
     */
    private Collection $paymentMethodIds;

    /**
     * @var Collection
     */
    private Collection $labelIds;

    /**
     * @var Collection
     */
    private Collection $paymentParentMethodIds;

    /**
     * @var Collection
     */
    private Collection $paymentTermIds;

    /**
     * @var int
     */
    private int $goodsCount;

    /**
     * @var Collection
     */
    private Collection $categoryIds;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->categoryOptionIds = collect();
        $this->goodsIds = collect();
        $this->groupIds = collect();
        $this->producerIds = collect();
        $this->seriesIds = collect();
        $this->optionIds = collect();
        $this->optionParentIds = collect();
        $this->optionValueIds = collect();
        $this->optionValueParentIds = collect();
        $this->optionSettingIds = collect();
        $this->constructorIds = collect();
        $this->paymentMethodIds = collect();
        $this->labelIds = collect();
        $this->paymentParentMethodIds = collect();
        $this->paymentTermIds = collect();
        $this->categoryIds = collect();
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @example php artisan db:generate-seed --category_ids=*80004 --main_dump=true --goods_count=10000
     * @return int
     */
    public function handle()
    {
        $isMainDump = \filter_var($this->option('main_dump'), FILTER_VALIDATE_BOOL);
        $this->path = $isMainDump
            ? config('filesystems.main_local_dump_path')
            : config('filesystems.secondary_local_dump_path');
        $this->goodsCount = (int) $this->option('goods_count');
        if ($this->goodsCount <=0) {
            $this->error('Nothing to do...');
            return 1;
        }

        File::cleanDirectory($this->path);
        if (!$isMainDump) {
            File::put($this->path . ".gitkeep", "");
        }

        $tables = [
            'categories' => [
                'queries' => function (): array {
                    $keys = Category::select(['left_key', 'right_key'])
                        ->whereIn('id', $this->option('category_ids'))
                        ->get();

                    $query = Category::query();
                    foreach ($keys as $categoryKeys) {
                        $query->orWhere(function ($query) use ($categoryKeys) {
                            $query->where('left_key', '>=', $categoryKeys['left_key'])
                                ->where('right_key', '<=', $categoryKeys['right_key']);
                        });
                    }
                    return [$query];
                },
                'callback' => function ($data): void {
                    $this->categoryIds = $this->categoryIds->merge($data->pluck('id'));
                },
            ],
            'goods' => [
                'queries' => function (): array {
                    $goodsLocked = Goods::query()
                        ->where('status', '=', 'locked')
                        ->whereIn('category_id', $this->categoryIds)
                        ->orderBy('id', 'desc')
                        ->limit($this->goodsCount * 0.1);
                    $query = Goods::query()
                        ->where('status', '=', 'active')
                        ->whereIn('category_id', $this->categoryIds)
                        ->orderBy('id', 'desc')
                        ->limit($this->goodsCount * 0.9)
                        ->union($goodsLocked);

                    return [$query];
                },
                'callback' => function ($data): void {
                    $this->goodsIds = $this->goodsIds->merge($data->pluck('id'));
                    $this->producerIds = $this->producerIds->merge($data->pluck('producer_id'))->unique();
                    $this->groupIds = $this->groupIds->merge($data->pluck('group_id')->filter(function ($value) {
                        return $value > 0;
                    }))->unique();
                    $this->seriesIds = $this->seriesIds->merge($data->pluck('series_id')->filter(function ($value) {
                        return $value > 0;
                    }))->unique();
                },
            ],
            'category_translations' => [
                'queries' => function (): array{
                    return $this->batchQuery(CategoryTranslation::query(), 'category_id', $this->categoryIds);
                },
            ],
            'category_options' => [
                'queries'  => function (): array {
                    return $this->batchQuery(CategoryOption::query(), 'category_id', $this->categoryIds);
                },
                'callback' => function ($data) {
                    $this->categoryOptionIds = $this->categoryOptionIds->merge($data->pluck('id'));
                },
            ],
            'category_option_translations' => [
                'queries' => function (): array {
                    return $this->batchQuery(CategoryOptionTranslation::query(), 'category_option_id', $this->categoryOptionIds);
                },
            ],
            'goods_translations' => [
                'queries' => function (): array {
                    return $this->batchQuery(GoodsTranslation::query(), 'goods_id', $this->goodsIds);
                },
            ],
            'bonuses' => [
                'queries' => function (): array {
                    return $this->batchQuery(Bonus::query(), 'goods_id', $this->goodsIds);
                },
            ],
            'goods_car_infos' => [
                'queries' => function (): array {
                    return $this->batchQuery(GoodsCarInfo::query(), 'goods_id', $this->goodsIds);
                },
            ],
            'goods_comments' => [
                'queries' => function (): array {
                    return $this->batchQuery(GoodsComment::query(), 'goods_id', $this->goodsIds);
                },
            ],
            'series' => [
                'queries' => function (): array {
                    return $this->batchQuery(Series::query(), 'id', $this->seriesIds);
                },
            ],
            'series_translations' => [
                'queries' => function (): array {
                    return $this->batchQuery(SeriesTranslation::query(), 'series_id', $this->seriesIds);
                },
            ],
            'producers' => [
                'queries' => function (): array {
                    return $this->batchQuery(Producer::query(), 'id', $this->producerIds);
                },
            ],
            'goods_option_booleans' => [
                'queries'  => function (): array {
                    return $this->batchQuery(GoodsOptionBoolean::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->optionIds = $this->optionIds->merge($data->pluck('option_id'))->unique();
                }
            ],
            'goods_option_numbers' => [
                'queries'  => function (): array {
                    return $this->batchQuery(GoodsOptionNumber::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->optionIds = $this->optionIds->merge($data->pluck('option_id'))->unique();
                }
            ],
            'goods_options_plural' => [
                'queries'  => function (): array {
                    return $this->batchQuery(GoodsOptionPlural::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->optionIds = $this->optionIds->merge($data->pluck('option_id'))->unique();
                    $this->optionValueIds = $this->optionValueIds->merge($data->pluck('value_id'))->unique();
                }
            ],
            'option_settings' => [
                'queries'  => function (): array {
                    return $this->batchQuery(
                        OptionSetting::query()->whereIn('category_id', $this->categoryIds->merge([0])),
                        'option_id',
                        $this->optionIds
                    );
                },
                'callback' => function ($data) {
                    $this->optionSettingIds = $this->optionSettingIds->merge($data->pluck('id')->unique());
                }
            ],
            'option_setting_translations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionSettingTranslation::query(), 'option_setting_id', $this->optionSettingIds);
                },
            ],
            'options' => [
                'queries'  => function (): array {
                    return $this->batchQuery(Option::query(), 'id', $this->optionIds);
                },
                'callback' => function ($data) {
                    $this->optionParentIds = $this->optionParentIds->merge($data->pluck('parent_id'))->unique();
                }
            ],
            /** Parents */
            'options_1' => [
                'queries'  => function (): array {
                    return $this->batchQuery(Option::query(), 'id', $this->optionParentIds);
                },
            ],
            'option_translations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionTranslation::query(), 'option_id', $this->optionIds->merge($this->optionParentIds));
                },
            ],
            'option_values' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionValue::query(),'id', $this->optionValueIds);
                },
                'callback' => function ($data) {
                    $this->optionValueParentIds = $this->optionValueParentIds->merge($data->pluck('parent_id'))->unique();
                }
            ],
            /** Parents */
            'option_values_1' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionValue::query(),'id', $this->optionValueParentIds);
                },
            ],
            'option_value_translations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionValueTranslation::query(), 'option_value_id', $this->optionValueIds);
                },
            ],
            'option_value_relations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionValueRelation::query(), 'value_id', $this->optionValueIds);
                },
            ],
            'option_value_category_relations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(OptionValueCategoryRelation::query(), 'value_id', $this->optionValueIds);
                },
            ],
            'goods_label' => [
                'queries'  => function (): array {
                    return $this->batchQuery(GoodsLabel::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->labelIds = $this->labelIds->merge($data->pluck('label_id'))->unique();
                }
            ],
            'labels' => [
                'queries'  => function (): array {
                    return $this->batchQuery(Label::query(), 'id', $this->labelIds);
                },
            ],
            'label_translations' => [
                'queries'  => function (): array {
                    return $this->batchQuery(LabelTranslation::query(), 'label_id', $this->labelIds);
                },
            ],
            'promotion_goods_constructors' => [
                'queries'  => function (): array {
                    return $this->batchQuery(PromotionGoodsConstructor::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->constructorIds = $this->constructorIds->merge($data->pluck('constructor_id'))->unique();
                }
            ],
            'promotion_groups_constructors' => [
                'queries'  => function (): array {
                    return $this->batchQuery(PromotionGroupConstructor::query(), 'group_id', $this->groupIds);
                },
                'callback' => function ($data) {
                    $this->constructorIds = $this->constructorIds->merge($data->pluck('constructor_id'))->unique();
                }
            ],
            'promotion_constructors' => [
                'queries'  => function (): array {
                    return $this->batchQuery(PromotionConstructor::query(), 'id', $this->constructorIds);
                },
            ],
            'goods_payment_method' => [
                'queries'  => function (): array {
                    return $this->batchQuery(GoodsPaymentMethod::query(), 'goods_id', $this->goodsIds);
                },
                'callback' => function ($data) {
                    $this->paymentMethodIds = $this->paymentMethodIds->merge($data->pluck('payment_method_id'))->unique();
                }
            ],
            'payment_methods' => [
                'queries'  => function (): array {
                    return $this->batchQuery(PaymentMethod::query(), 'id', $this->paymentMethodIds);
                },
                'callback' => function ($data) {
                    $this->paymentParentMethodIds = $this->paymentParentMethodIds->merge(
                        $data->pluck('parent_id')->unique()->filter(function ($value) {
                            return null !== $value;
                        })
                    )->unique();
                    $this->paymentTermIds = $this->paymentTermIds->merge(
                        $data->pluck('payment_term_id')->unique()->filter(function ($value) {
                            return null !== $value;
                        })
                    )->unique();

                }
            ],
            'payment_method_translations' => [
                'queries' => function (): array {
                    return $this->batchQuery(PaymentMethodTranslation::query(), 'payment_method_id', $this->paymentMethodIds);
                },
            ],
            'payment_parent_methods' => [
                'queries' => function (): array {
                    return $this->batchQuery(PaymentParentMethod::query(), 'id', $this->paymentParentMethodIds);
                },
            ],
            'payment_parent_method_translations' => [
                'queries' => function (): array {
                    return $this->batchQuery(PaymentParentMethodTranslation::query(), 'payment_parent_method_id', $this->paymentParentMethodIds);
                },
            ],
            'payment_methods_terms' => [
                'queries' => function (): array {
                    return $this->batchQuery(PaymentMethodsTerm::query(), 'id', $this->paymentTermIds);
                },
            ],
            'precount_option_settings' => [
                'queries' => function (): array {
                    return $this->batchQuery(PrecountOptionSetting::query(), 'options_settings_id', $this->optionSettingIds);
                },
            ],
            'precount_option_sliders' => [
                'queries' => function (): array {
                    return $this->batchQuery(PrecountOptionSlider::query(), 'option_id', $this->optionIds);
                },
            ],
//            'filters_autoranking' => [
//                'queries' => function (): array {
//                    return [FilterAutoranking::query()];
//                },
//            ],
        ];

        foreach ($tables as $tableName => $options) {
            $this->handleTable($tableName, $options['queries'](), $options['callback'] ?? null);
        }

        return 0;
    }

    /**
     * Опрацьовує запити і зберігає дані в файли
     *
     * @param string $title
     * @param array $queries
     * @param callable|null $callback
     *
     * @return void
     */
    private function handleTable(string $title, array $queries, callable $callback = null): void
    {
        $ready = $percent = $records = 0;
        $start = microtime(true);
        $titleConsole = \str_pad("$title", 35, '.');
        $this->getOutput()->write("<fg=yellow>$titleConsole</> <fg=green>$percent%</> ");
        File::put($this->path . "$title.json", "[\n");

        $firstIteration = true;
        foreach ($queries as $query) {
            foreach ($query->trueCursor($this->maxBatch) as $data) {
                $toFile = \trim(\json_encode($data, JSON_UNESCAPED_UNICODE), '[]');
                if (!$firstIteration) {
                    $toFile = ",\n$toFile";
                }
                $firstIteration = false;
                File::append($this->path . "$title.json", $toFile);
                if (null !== $callback && \is_callable($callback)) {
                    $callback($data);
                }

                if ('goods' === $title) {
                    $records = $records + \sizeof($data);
                    $percent = round($records / $this->goodsCount * 100);
                    $this->getOutput()->write("\r<fg=yellow>$titleConsole</> <fg=green>$percent%</> ");
                }
            }

            if ('goods' !== $title) {
                $percent = round(++$ready / \sizeof($queries) * 100);
                $this->getOutput()->write("\r<fg=yellow>$titleConsole</> <fg=green>$percent%</> ");
            }
        }

        File::append($this->path . "$title.json", "\n]");
        $time = \round(microtime(true) - $start, 2);
        $this->getOutput()->write("\r<fg=yellow>$titleConsole</><fg=green> 100% ($time sec.)</>\n");
    }

    /**
     * Повертає множені запити, з розбитими значеннями умови $whereIn
     *
     * @param Builder $query
     * @param string $column
     * @param Collection $whereIn
     *
     * @return array
     */
    private function batchQuery(Builder $query, string $column, Collection $whereIn): array
    {
        $queries = [];
        foreach ($whereIn->chunk($this->whereInChunk) as $chunk) {
            $queries[] = $query->clone()->whereIn($column, $chunk);
        }

        return $queries;
    }
}
