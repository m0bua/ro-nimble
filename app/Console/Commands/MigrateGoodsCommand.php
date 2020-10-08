<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\QueryBuilderHelper;

use App\Console\Commands\Extend\CustomCommand;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

use App\Models\GraphQL\GoodsBatchModel;
use App\Traits\MigrateGoodsCommandTrait;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MigrateGoodsCommand
 * @package App\Console\Commands
 */
class MigrateGoodsCommand extends CustomCommand
{
    use MigrateGoodsCommandTrait;

    public const ENTITY_GOODS = 'goods';
    public const ENTITY_GROUPS = 'groups';

    /** @var string */
    protected $signature = 'db:migrate-goods {--e|entity=}';

    /** @var string */
    protected $description = 'Migrate goods from db store to db nimble';

    /** @var string */
    protected string $entity;

    /** @var GoodsBatchModel */
    protected GoodsBatchModel $batch;

    /**
     * Конструктор MigrateGoodsCommand
     * @param GoodsBatchModel $batch
     */
    public function __construct(GoodsBatchModel $batch)
    {
        $this->batch = $batch;

        parent::__construct();
    }

    /** @return array[] */
    protected function getOptions(): array
    {
        return [
            ['entity', 'e', InputOption::VALUE_REQUIRED, 'Process entity.', self::ENTITY_GOODS],
        ];
    }

    /** @return void */
    public function handle(): void
    {
        $this->entity = $this->option('entity') ?: self::ENTITY_GOODS;
        $config = $this->getEntityConfig();
        $this->batch->setWhereInField($config['batch_field']);

        $this->catchExceptions(function () use ($config) {
            $query = $this->getQuery($config);
            if (!$query) {
                $this->error('An error occurred while generating the query.');
                return;
            }

            QueryBuilderHelper::chunkByPrimary($query, function ($data) use ($config): void {
                $ids = \array_map(function ($item) use ($config) {
                    if ($this->entity === self::ENTITY_GOODS) {
                        $property = $config['goods_join_field'];
                        if ($item->$property) {
                            return null;
                        }
                    }

                    $property = $config['field'];
                    return $item->$property;
                }, $data);
                $ids = \array_filter(\array_unique($ids));

                if ($ids) {
                    $this->batch->getByBatch($ids, function ($nodes): void {
                        $dataArray = $this->formatGoodsNodes($nodes);
                        foreach ($dataArray as $table => $data) {
                            DB::table($table)->insertOrIgnore($data);
                        }
                    });
                }

                DB::table($config['table'])
                    ->whereIn($config['field'], \array_column($data, $config['field']))
                    ->update(['needs_migrate' => 0]);
            });
        });
    }

    /**
     * @param array $config
     * @param int $indexCondition
     * @return Builder|null
     */
    private function getQuery(array $config, int $indexCondition = 1): ?Builder
    {
        $table = $config['table'];
        $searchField = $config['field'];
        $joinField = $config['goods_join_field'];

        $query = null;
        switch ($this->entity) {
            case self::ENTITY_GROUPS:
                $query = DB::table("{$table} as main_table")
                    ->select([
                        'main_table.id as primary_id',
                        "main_table.{$searchField}",
                    ])
                    ->where(['needs_migrate' => $indexCondition]);
                break;
            case self::ENTITY_GOODS:
                $query = DB::table("{$table} as main_table")
                    ->select([
                        'main_table.id as primary_id',
                        "main_table.{$searchField}",
                        "goods.{$joinField}"
                    ])
                    ->leftJoin(
                        'goods',
                        "main_table.{$searchField}",
                        '=',
                        "goods.{$joinField}"
                    )
                    ->where(['main_table.needs_migrate' => $indexCondition]);
        }

        return $query;
    }

    /** @return int[] */
    private function getEntityConfig(): array
    {
        switch ($this->entity) {
            case self::ENTITY_GROUPS:
                $config = [
                    'table' => 'promotion_groups_constructors',
                    'field' => 'group_id',
                    'goods_join_field' => 'group_id',
                    'batch_field' => 'group_id'
                ];
                break;
            case self::ENTITY_GOODS:
            default:
                $config = [
                    'table' => 'promotion_goods_constructors',
                    'field' => 'goods_id',
                    'goods_join_field' => 'id',
                    'batch_field' => 'id'
                ];
        }

        return $config;
    }
}
