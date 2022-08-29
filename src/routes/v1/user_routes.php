<?php

use App\Http\Controllers\API\v1\User\UserController;
use Illuminate\Support\Facades\Route;


//Authenticatoin Routes
Route::prefix('/users')->group(function (){

    Route::get('/leaderboards',[
        UserController::class,
        'user'
    ])->name('users.leaderboards');

});
