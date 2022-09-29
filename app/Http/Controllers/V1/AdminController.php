<?php

namespace App\Http\Controllers\V1;

use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use App\Models\Elastic\ProducersModel;
use App\Models\Eloquent\Indices;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Elasticsearch goods model
     *
     * @var Elastic
     */
    private Elastic $goodsElastic;

    public function __construct(ResponseFactory $responseFactory, GoodsModel $goodsElastic)
    {
        $this->goodsElastic = $goodsElastic;

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
            ], 200)
            ->header('Content-Type', 'text/html');
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
