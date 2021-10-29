<?php

use App\Http\Controllers\V1\FiltersController;
use App\Http\Controllers\V1\GoodsController;
use App\Http\Controllers\V1\IndexController;

Route::get('/', [IndexController::class, 'index']);
Route::get('/goods', [GoodsController::class, 'index']);
Route::get('/goods/details', [GoodsController::class, 'details']);
Route::get('/filters', [FiltersController::class, 'index']);

