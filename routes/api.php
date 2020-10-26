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

Route::middleware('api')->group(function() {
    // Customers API routes
    Route::get('/customers/names/{year}', 'App\Http\Controllers\Customers\CustomersServicesController@names');
    Route::get('/customers/recipient/{id}', 'App\Http\Controllers\Customers\CustomersServicesController@recipient');
    Route::get('/customers/{customer}/{year}/{edit}/getgroup', 'App\Http\Controllers\Customers\CustomersServicesController@getGroup');
    // Receipts API routes
    Route::get('/receipts/{number}/{year}/info', 'App\Http\Controllers\Receipts\ReceiptsServicesController@info');
    Route::get('/receipts/{id}/{number}/{year}/quota', 'App\Http\Controllers\Receipts\ReceiptsServicesController@customerQuota');
    Route::get('/receipts/years/{id}/{year}', 'App\Http\Controllers\Receipts\ReceiptsServicesController@years');
    Route::get('/receipts/{number}/{year}/print', 'App\Http\Controllers\Receipts\ReceiptsServicesController@printReceipt');
    // Rates API routes
    Route::get('/rates/{id}/quota', 'App\Http\Controllers\Rates\RatesServicesController@quota');
    Route::get('/rates/exceptions/{year}/customers', 'App\Http\Controllers\Rates\FuneralExceptionsController@deadCustomers');
});
