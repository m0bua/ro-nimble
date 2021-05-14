<?php

use App\Http\Controllers\V2\IndexController;

Route::get('/', [IndexController::class, 'index']);
