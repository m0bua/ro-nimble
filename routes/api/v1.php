<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('v1')->group(function() {
    Route::get('/goods', [\App\Http\Controllers\v1\GoodsController::class, 'index']);
});
