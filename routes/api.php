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

Route::middleware('api')->group(function () {
    // Customers API routes
    Route::get(
        '/customers/{year}/{id}/names',
        'App\Http\Controllers\Customers\CustomersServicesController@customersNamesPerYear'
    );
    Route::get('/customers/{id}/recipient', 'App\Http\Controllers\Customers\CustomersServicesController@recipient');
    Route::get(
        '/customers/{customer}/{year}/{edit}/group',
        'App\Http\Controllers\Customers\CustomersServicesController@getGroup'
    );
    Route::get('/customers/{id}/info', 'App\Http\Controllers\Customers\CustomersServicesController@customerInfo');

    // Receipts API routes
    Route::get(
        '/receipts/{receipt_number}/{year}/info',
        'App\Http\Controllers\Receipts\ReceiptsServicesController@info'
    );
    Route::get(
        '/receipts/{id}/{receipt_number}/{year}/quota',
        'App\Http\Controllers\Receipts\ReceiptsServicesController@customerQuota'
    );
    Route::get(
        '/receipts/years/{id}/{year}',
        'App\Http\Controllers\Receipts\ReceiptsServicesController@years'
    );
    Route::get(
        '/receipts/{receipt_number}/{year}/print',
        'App\Http\Controllers\Receipts\ReceiptsServicesController@printReceipt'
    );

    // Rates API routes
    Route::get('/rates/{id}/quota', 'App\Http\Controllers\Rates\RatesServicesController@quota');
    Route::get(
        '/rates/exceptions/{year}/customers',
        'App\Http\Controllers\Rates\FuneralExceptionsServicesController@deadCustomers'
    );

    // Offers API routes
    Route::get('/offers/{id}/print', 'App\Http\Controllers\OffersController@printReceipt');

    // Expenses API routes
    Route::get('/expenses/{id}/print', 'App\Http\Controllers\ExpensesController@printReceipt');
});
