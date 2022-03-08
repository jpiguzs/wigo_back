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


Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('check', 'AuthController@check');
    Route::get('me', 'AuthController@me');
    Route::post('register', 'AuthController@register');
    Route::post('recovery_password', 'AuthController@recovery_password');
    Route::post('verify_pin', 'AuthController@verify_pin');
    Route::post('change_password', 'AuthController@change_password');
});
Route::group([
    'prefix' => 'budget'
], function(){
    Route::post('register', 'BudgetController@register');
    Route::get('index', 'BudgetController@index');
    Route::get('list_user_budget', 'BudgetController@list_user_budget');
    Route::get('budget/{$id}', 'BudgetController@getBudgetById');
});
