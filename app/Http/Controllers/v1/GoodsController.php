<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\v1\GoodsResource;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'ids' => [1,2,3,4,5]
        ];

        return GoodsResource::make($data);
    }
}
