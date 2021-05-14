<?php
/** @noinspection PhpIncludeInspection */

Route::prefix('v1')->group(function () {
    require base_path('routes/api/v1.php');
});

Route::prefix('v2')->group(function () {
    require base_path('routes/api/v2.php');
});
