<?php

use App\Http\Controllers\V1\CategoryChildrenController;
use App\Http\Controllers\V1\FilterController;
use App\Http\Controllers\V1\GoodsController;
use App\Http\Controllers\V1\GoodsDetailsController;
use App\Http\Controllers\V1\IndexController;
use App\Http\Controllers\V1\SearchBrandsController;

Route::get('/', [IndexController::class, 'index']);
Route::get('/goods', [GoodsController::class, 'index']);
Route::get('/goods/details', [GoodsDetailsController::class, 'index']);
Route::get('/filters', [FilterController::class, 'index']);
Route::get('/categories/children', [CategoryChildrenController::class, 'index']);
Route::get('/search/brands', [SearchBrandsController::class, 'index']);
