<?php

declare(strict_types=1);

namespace App\Console\Commands\Migrate;

use App\Console\Commands\Command;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Models\GraphQL\GoodsBatchModel;
use App\Support\Language;
use App\Traits\MigrateGoodsCommandTrait;
use DomainException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MigrateGoods extends Command
{
    use MigrateGoodsCommandTrait;

    public const ENTITY_GOODS = 'goods';
    public const ENTITY_GROUPS = 'groups';

    public const CHUNK_COUNT = 500;

    /**
     * @var string
     */
    protected $signature = 'db:migrate-goods {--entity=}';

    /**
     * @var string
     */
    protected $description = 'Migrate goods from db store to db nimble';

    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var array<string>
     */
    protected array $config;

    /**
     * @var GoodsBatchModel
     */
    protected GoodsBatchModel $graphQlBatchModel;

    /**
     * @var PromotionGoodsConstructor|PromotionGroupConstructor
     */
    protected Model $model;

    /**
     * MigrateGoods constructor.
     * @param GoodsBatchModel $graphQlBatchModel
     */
    public function __construct(GoodsBatchModel $graphQlBatchModel)
    {
        $this->graphQlBatchModel = $graphQlBatchModel;

        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function proceed(): void
    {
        // Setting up the command
        $this->entity = $this->option('entity') ?? self::ENTITY_GOODS;
        $this->config = $this->resolveConfig();
        $this->model = $this->resolveCommandModel();

        // Configuring GraphQL model
        $this->graphQlBatchModel->setWhereInField($this->config['batch_field']);

        // Chunk by constructors id, fetch and save missed goods
        $this->buildQuery()
            ->chunkById(self::CHUNK_COUNT, [$this, 'processChunk']);
    }

    /**
     * @return array<string>
     */
    private function resolveConfig(): array
    {
        switch ($this->entity) {
            case self::ENTITY_GROUPS:
                return [
                    'field' => 'group_id',
                    'goods_join_field' => 'group_id',
                    'batch_field' => 'group_id'
                ];
            case self::ENTITY_GOODS:
            default:
                return [
                    'field' => 'goods_id',
                    'goods_join_field' => 'id',
                    'batch_field' => 'id'
                ];
        }
    }

    /**
     * @return PromotionGoodsConstructor|PromotionGroupConstructor
     */
    protected function resolveCommandModel()
    {
        switch ($this->entity) {
            case self::ENTITY_GROUPS:
                return new PromotionGroupConstructor();
            case self::ENTITY_GOODS:
            default:
                return new PromotionGoodsConstructor();
        }
    }

    /**
     * @return Builder
     */
    protected function buildQuery(): Builder
    {
        if (!in_array($this->entity, [self::ENTITY_GOODS, self::ENTITY_GROUPS])) {
            throw new DomainException('An error occurred while generating the query.');
        }

        return $this->model
            ->needsMigrate()
            ->select([
                'id',
                $this->config['field'],
            ])
            ->when(
                $this->entity === self::ENTITY_GOODS,
                fn(Builder $q) => $q->with('goods:id')
            );
    }

    /**
     * @param Collection|array<PromotionGoodsConstructor>|array<PromotionGroupConstructor> $collection
     * @throws Exception
     */
    public function processChunk(Collection $collection): void
    {
        $ids = $this->pluckIdsFromCollection($collection);

        $this->graphQlBatchModel->getByBatch($ids, fn(array $nodes) => $this->saveNodes($nodes));

        $field = $this->config['field'];

        $this->model
            ->whereIn($field, $collection->pluck($field))
            ->update(['needs_migrate' => 0]);
    }

    /**
     * @param Collection $collection
     * @return array
     */
    protected function pluckIdsFromCollection(Collection $collection): array
    {
        return $collection
            ->map(function ($entity) {
                /** @var PromotionGoodsConstructor|PromotionGroupConstructor $entity */
                if (($this->entity === self::ENTITY_GOODS) && $entity->goods->exists) {
                    return null;
                }

                $property = $this->config['field'];
                return $entity->$property;
            })
            ->filter()
            ->unique()
            ->toArray();
    }

    /**
     * @param array $nodes
     * @throws Exception
     */
    protected function saveNodes(array $nodes): void
    {
        $data = $this->formatGoodsNodes($nodes);

        foreach ($data as $table => $items) {
            $tableModel = self::resolveModelByTable($table);
            foreach ($items as $item) {
                self::saveModel($tableModel, $item);
            }
        }
    }

    /**
     * @param string $table
     * @return Model
     */
    private static function resolveModelByTable(string $table): Model
    {
        $str = Str::of($table)->studly();

        if ($table === 'goods_options_plural') {
            $str = $str->replace('Options', 'Option');
        } elseif ($table !== 'goods') {
            $str = $str->singular();
        }

        $modelNamespace = "App\Models\Eloquent\\$str";

        if (class_exists($modelNamespace)) {
            return new $modelNamespace();
        }

        throw new DomainException("Unknown model namespace $modelNamespace");
    }

    /**
     * @param Model $model
     * @param array $data
     * @throws Exception
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection TypeUnsafeComparisonInspection
     */
    private static function saveModel(Model $model, array $data): void
    {
        // Firstly we create an entity
        $entity = (clone $model)->forceFill(Arr::only($data, $model->getFillable()));

        try {
            // Trying to save it
            $entity->save();
        } catch (Exception $e) {
            // If entity already exists => skip it
            if ($e->getCode() == 23505) {
                return;
            }

            throw $e;
        }

        // Secondly we save its translations
        $translatable = $model->getTranslatableProperties();

        if ($translatable) {
            $ruTranslations = Arr::only($data, $translatable);
            $ukTranslations = Arr::only($data['uk'] ?? [], $translatable);

            $translations = self::mergeTranslations($ruTranslations, $ukTranslations);
            $entity->forceFill($translations);
        }
    }

    /**
     * @param array $ruTranslations
     * @param array $ukTranslations
     * @return array
     */
    private static function mergeTranslations(array $ruTranslations, array $ukTranslations): array
    {
        // Comparing two arrays for iterate bigger one
        $biggerArray = count($ruTranslations) >= count($ukTranslations) ? 'ruTranslations' : 'ukTranslations';
        $smallerArray = $biggerArray === 'ruTranslations' ? 'ukTranslations' : 'ruTranslations';

        // Resolving main language depending bigger array
        $primaryLang = $biggerArray === 'ruTranslations' ? Language::RU : Language::UK;
        $auxiliaryLang = $primaryLang === Language::RU ? Language::UK : Language::RU;

        $translations = [];
        foreach ($$biggerArray as $key => $translation) {
            $translations[$key][$primaryLang] = $translation;

            if (isset(${$smallerArray}[$key]) && ${$smallerArray}[$key] !== '') {
                $translations[$key][$auxiliaryLang] = ${$smallerArray}[$key];
            }
        }

        return $translations;
    }
}
