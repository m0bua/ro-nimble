<?php

namespace App\Http\Controllers\V1;

use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use App\Models\Elastic\ProducersModel;
use App\Models\Eloquent\Indices;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class AdminController extends Controller
{
    /**
     * Elasticsearch goods model
     *
     * @var Elastic
     */
    private Elastic $goodsElastic;

    /**
     * @var Collection
     */
    public Collection $navbar;

    public static $navbarTitles = [
        'import' => 'Need Delete',
    ];

    public function __construct(ResponseFactory $responseFactory, GoodsModel $goodsElastic)
    {
        $this->goodsElastic = $goodsElastic;

        $this->navbar = collect(Route::getRoutes()->getRoutes())->filter(function($item) {
            return !empty($item->action['as']) && false !== \strpos($item->action['as'], 'admin.navbar');
        })->map(function ($item) {
            $action = \explode('@', $item->action['controller'])[1];
            return [
                'uri'    => "/$item->uri",
                'active' => $item->action['as'] === \Request::route()->getName(),
                'title'  => self::$navbarTitles[$action] ?? \ucfirst($action)
            ];
        });

        parent::__construct($responseFactory);
    }

    public function index()
    {
        $goodsAlias = $this->goodsElastic->getIndexWithAlias();
        $goodsIndices = $this->goodsElastic->indexInfo()->map(function ($item) use ($goodsAlias) {
            $item['db_status'] = Indices::getStatus($item['index']);
            $item['actions'] = $item['index'] === $goodsAlias;
            return $item;
        });

        $refillActive = str_contains(
            shell_exec("ps aux | grep 'artisan index:refill'"), 'php artisan index:refill'
        );
        return response()
            ->view('admin.index', [
                'goodsIndices' => $goodsIndices,
                'refillActive' => $refillActive,
                'navbar'       => $this->navbar,
            ], 200)
            ->header('Content-Type', 'text/html');
    }

    public function import()
    {
        $tables = DB::query()
            ->select(['t.table_name'])
            ->from('information_schema.tables', 't')
            ->rightJoin('information_schema.columns as s', function ($join) {
                $join->on('s.table_name', '=', 't.table_name')
                    ->where('s.column_name', '=', 'need_delete');
            })
            ->whereNotIn('t.table_schema', ['information_schema', 'pg_catalog'])
            ->where('t.table_type', '=', 'BASE TABLE')
            ->get()
            ->map(function ($item) {
                $item->count = DB::query()
                    ->select(DB::raw('count(1) as count'))
                    ->from($item->table_name)
                    ->where('need_delete', '=', 1)
                    ->pluck('count')
                    ->first();
                return (array) $item;
            });

        return response()
            ->view('admin.import', [
                'tables' => $tables,
                'navbar' => $this->navbar,
                'mark4DeleteActive' => str_contains(
                    shell_exec("ps aux | grep 'artisan db:mark-for-delete'"), 'php artisan db:mark-for-delete'
                ),
                'deleteFromDBActive' => str_contains(
                    shell_exec("ps aux | grep 'artisan db:delete-from-db'"), 'php artisan db:delete-from-db'
                )
            ], 200)
            ->header('Content-Type', 'text/html');
    }

    public function markDelete()
    {
        $basePath = base_path();
        $params = '';
        foreach (Request::post('tables_select') as $table) {
            $params .= " --tables=$table";
        }
        exec("cd $basePath && php artisan db:mark-for-delete $params > /dev/null &");
        return redirect('/api/v1/admin/import');
    }

    public function deleteFromDb()
    {
        $basePath = base_path();
        $params = '';
        foreach (Request::post('tables_select') as $table) {
            $params .= " --tables=$table";
        }
        exec("cd $basePath && php artisan db:delete-from-db $params > /dev/null &");
        return redirect('/api/v1/admin/import');
    }

    public function switch()
    {
        try {
            $index = $this->goodsElastic->indexInfo(\Request::get('name', null))->first();
            $activeIndexName = $this->goodsElastic->getIndexWithAlias();

            if ($activeIndexName !== $index['index']) {
                Indices::upsert([
                    'name'   => $activeIndexName,
                    'type'   => $this->goodsElastic->indexPrefix(),
                    'status' => Indices::STATUS_LOCKED
                ], 'name');
                Indices::upsert([
                    'name'   => $index['index'],
                    'type'   => $this->goodsElastic->indexPrefix(),
                    'status' => Indices::STATUS_ACTIVE
                ], 'name');

                $this->goodsElastic->updateAliases([
                    $this->goodsElastic->addAliasAction($index['index'], $this->goodsElastic->indexPrefix()),
                    $this->goodsElastic->removeAliasAction($activeIndexName, $this->goodsElastic->indexPrefix()),
                ]);
            }
        } catch (\Throwable $t) {
            dd($t);
        } finally {
            return redirect('/api/v1/admin');
        }
    }

    public function delete()
    {
        try {
            $index = $this->goodsElastic->indexInfo(\Request::get('name', null))->first();
            $activeIndexName = $this->goodsElastic->getIndexWithAlias();

            if ($activeIndexName !== $index['index']) {
                $this->goodsElastic->deleteIndex($index['index']);
            }
        } catch (\Throwable $t) {
            dd($t);
        } finally {
            return redirect('/api/v1/admin');
        }
    }

    public function runRefill()
    {
        $basePath = base_path();
        exec("cd $basePath && php artisan index:refill > /dev/null &");
        return redirect('/api/v1/admin');
    }
}
