<?php
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
Route::group(['prefix' => 'users', 'middleware' => 'signature'], function () {

    Route::group(['prefix' => 'kycs'], function () {
        Route::get('/', 'KycController@index');
        Route::post('/create', 'KycController@store');
        Route::get('/{id}', 'KycController@show');
        Route::get('/{id}/check', 'KycController@check');
        Route::get('/{id}/percentage', 'KycController@percentage');
        Route::post('/{id}/update', 'KycController@update');
        Route::post('/{id}/edit', 'KycController@edit');
        Route::delete('/{id}/delete', 'KycController@destroy');
    });
});

Route::group(['prefix' => 'accounts', 'middleware' => 'signature'], function () {
    Route::group(['prefix' => 'requests', 'namespace' => 'Apis'], function () {
        Route::get('/', 'AccountRequestController@index');
        Route::get('count', 'AccountRequestController@count');
        Route::get('{id}/show', 'AccountRequestController@show');
        Route::get('{id}/upgrade', 'AccountRequestController@upgrade');
        Route::delete('{id}/delete', 'AccountRequestController@destroy');

        //send requests and mails
        Route::post('{id}/shutdown', 'AccountRequestController@shutdownRequest');
        Route::post('{id}/activate', 'AccountRequestController@activateRequest');
    });

    // accounts/actions/
    Route::group(['prefix' => 'actions'], function () {
        Route::get('{id}/reject-premium', 'UserController@premium');
        Route::get('{id}/{type}/shutdown', 'UserController@shutdown');
        Route::get('{id}/{type}/decision', 'UserController@action');
        Route::get('{id}/{type}/edit-status', 'UserController@edit');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@customers');
        Route::get('/{id}', 'UserController@customer');
        Route::get('/{id}/activate', 'UserController@activate');
        Route::post('/{id}/verify', 'UserController@verification');
        Route::post('/{id}/update', 'UserController@updateKyc');
    });
});
