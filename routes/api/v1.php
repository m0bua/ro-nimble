<?php

use App\Http\Controllers\V1\{
    CategoryChildrenController,
    FilterController,
    GoodsController,
    GoodsDetailsController,
    IndexController,
    SearchBrandsController,
    AdminController
};

Route::get('/', [IndexController::class, 'index']);
Route::get('/goods', [GoodsController::class, 'index']);
Route::get('/goods/details', [GoodsDetailsController::class, 'index']);
Route::get('/filters', [FilterController::class, 'index']);
Route::get('/categories/children', [CategoryChildrenController::class, 'index']);
Route::get('/search/brands', [SearchBrandsController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index'])->name('admin.navbar.index');
Route::get('/admin/switch', [AdminController::class, 'switch']);
Route::get('/admin/delete', [AdminController::class, 'delete']);
Route::get('/admin/run-refill', [AdminController::class, 'runRefill']);
Route::get('/admin/import', [AdminController::class, 'import'])->name('admin.navbar.import');
Route::post('/admin/mark-delete', [AdminController::class, 'markDelete']);
Route::post('/admin/delete-from-db', [AdminController::class, 'deleteFromDb']);
