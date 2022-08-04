<?php

use App\Http\Controllers\API\v1\Thread\ThreadController;
use Illuminate\Support\Facades\Route;

//Thread Routes
Route::prefix('/threads')->group(function () {

    Route::get('/all', [
        ThreadController::class,
        'getAllThreadsList'
    ])->name('thread.all');


    Route::get('/show', [
        ThreadController::class,
        'getThread'
    ])->name('thread.show');



});
