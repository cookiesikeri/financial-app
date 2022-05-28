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
Route::group(['namespace' => 'Apis', 'middleware' => 'signature'], function () {
    //GENERAL ROUTES
    Route::group(['prefix' => 'general'], function () {
        Route::get('{endpoint}', 'RestfulController@index');
        Route::post('{endpoint}/create', 'RestfulController@store');
        Route::get('{endpoint}/{id}', 'RestfulController@show');
        Route::get('{endpoint}/count', 'RestfulController@count');
        Route::post('{endpoint}/{id}/update', 'RestfulController@update');
        Route::delete('{endpoint}/{id}/delete', 'RestfulController@destroy');
    });

    Route::group(['namespace' => 'loans'], function () {
        //LOAN ROUTES
        Route::group(['prefix' => 'loans'], function () {
            Route::get('/{id}/user', 'LoanController@index');
            Route::post('create', 'LoanController@store');
            Route::get('{id}', 'LoanController@show');
            //Route::post('{id}/update', 'LoanController@update');
            Route::post('calculate', 'LoanController@calculate');
            Route::post('{id}/repay', 'LoanController@repay');
            Route::delete('{id}/delete', 'LoanController@destroy');

            //Route::post('login', 'LoanController@login');
            Route::get('auth/users', 'LoanController@users');

        });
        //LOAN ACCOUNT ROUTES
        Route::group(['prefix' => 'loan-accounts'], function () {
            Route::get('/', 'LoanAccountController@index');
            Route::post('create', 'LoanAccountController@store');
            Route::get('{id}', 'LoanAccountController@show');
            Route::get('{id}/pending-loans', 'LoanAccountController@pending');
            Route::post('{id}/update', 'LoanAccountController@update');
            Route::delete('{id}/delete', 'LoanAccountController@destroy');

        });
        //PAYMENT CARDS ROUTES
        Route::group(['prefix' => 'payment-cards'], function () {
            Route::get('/', 'PaymentCardController@index');
            Route::post('create', 'LoanAccountController@store');
            Route::get('{id}', 'PaymentCardController@show');
            Route::get('{bin}/validate', 'PaymentCardController@check');
            Route::delete('{id}/delete', 'PaymentCardController@destroy');
        });
        Route::get('cards/banks/{id}', 'PaymentCardController@bank');
        Route::get('cards/banks', 'PaymentCardController@banks');
    });
});
