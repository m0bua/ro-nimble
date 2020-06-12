<?php

namespace App\Http\Controllers;

use App\Services\GoodsService;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods(GoodsService $goodsService)
    {
        dump(
            $goodsService->getById(200775625)
        );
        die;

        return 123;
    }
}
