<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
    public function index(Request $request)
    {
        /**
         * TODO реализовать логику формирования фильтров
         * при передаче пустого массива [] в ответе будет выводиться пример структуры ответа
         */

        $t = [
            'anchors' => [
                'bottom' => [
                    [
                        'title' => 'test',
                        'href' => 'test_href',
                    ],
                    [
                        'title' => 'test2',
                        'href' => 'test_href2'
                    ]
                ]
            ]
        ];
        return FilterResource::make($t);
    }
}
