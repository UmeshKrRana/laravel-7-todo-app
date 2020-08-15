<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("create-user", "UserController@createUser");

Route::post("user-login", "UserController@userLogin");

Route::group(['middleware' => 'auth:api'], function () {

    Route::get("user-detail", "UserController@userDetail");

    Route::post("create-task", "TaskController@createTask");

    Route::get("tasks", "TaskController@tasks");

    Route::get("task/{task_id}", "TaskController@task");

    Route::delete("task/{task_id}", "TaskController@deleteTask");

});

