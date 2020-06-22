<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use App\Services\GoodsService;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods(GoodsService $goodsService, ElasticsearchService $elasticsearchService)
    {
        $id = 200775625;
        $id = 97653;
        $id = 198516121;

        $good = $goodsService->getById($id);

        dump(
            $good
        );
        die;

        $response = $elasticsearchService->setGood($good);

        dump(
            $response
        );
        die;

        $search = $elasticsearchService->searchGood($id);

        dump(
            $search
        );
        die;

        return $response;
    }
}
