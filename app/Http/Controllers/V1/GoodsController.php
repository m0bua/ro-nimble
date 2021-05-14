<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GoodsResource;

class GoodsController extends Controller
{
    /**
     * @return GoodsResource
     */
    public function index(): GoodsResource
    {
        $data = [
            'ids' => [1, 2, 3, 4, 5]
        ];

        return GoodsResource::make($data);
    }
}
