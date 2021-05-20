<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'options' => [
                [
                    'option_id' => 1,
                    'option_values' => [
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                    ],
                ],
                [
                    'option_id' => 2,
                    'option_values' => [
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                    ],
                ],
                [
                    'option_id' => 3,
                    'option_values' => [
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                        [
                            'option_value_id' => 10,
                            'is_chosen' => false,
                            'products_quantity' => 1,
                        ],
                    ],
                ],
            ],
            'chosen' => ['something']
        ];

        return FilterResource::make($data);
    }
}
