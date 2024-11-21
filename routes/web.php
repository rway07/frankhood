<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'App\Http\Controllers\Receipts\ReceiptsController@index');

    // Customer routes
    Route::get('/customers/index', 'App\Http\Controllers\Customers\CustomersController@index');
    Route::any('/customers/data', 'App\Http\Controllers\Customers\CustomersController@data');
    Route::get('/customers/create', 'App\Http\Controllers\Customers\CustomersController@create');
    Route::post('/customers/store', 'App\Http\Controllers\Customers\CustomersController@store');
    Route::delete('/customers/{id}/delete', 'App\Http\Controllers\Customers\CustomersController@destroy');
    Route::get('/customers/{id}/edit', 'App\Http\Controllers\Customers\CustomersController@edit');
    Route::match(
        [
            'put',
            'patch'
        ],
        '/customers/{id}/update',
        'App\Http\Controllers\Customers\CustomersController@update'
    );
    Route::get('/customers/{id}/summary', 'App\Http\Controllers\Customers\CustomersController@summary');

    // Receipts routes
    Route::get('/receipts/index', 'App\Http\Controllers\Receipts\ReceiptsController@index');
    Route::get('/receipts/create', 'App\Http\Controllers\Receipts\ReceiptsController@create');
    Route::get('/receipts/{year}/{id}/data', 'App\Http\Controllers\Receipts\ReceiptsDataController@data');
    Route::post('/receipts/store', 'App\Http\Controllers\Receipts\ReceiptsController@store');
    Route::get('/receipts/{receipt_number}/{year}/edit', 'App\Http\Controllers\Receipts\ReceiptsController@edit');
    Route::delete(
        '/receipts/{receipt_number}/{year}/delete',
        'App\Http\Controllers\Receipts\ReceiptsController@destroy'
    );
    Route::match(
        ['put', 'patch'],
        '/receipts/{receipt_number}/{year}/update',
        'App\Http\Controllers\Receipts\ReceiptsController@update'
    );
    Route::post('/receipts/done', 'App\Http\Controllers\Receipts\ReceiptsController@done');

    // Rates routes
    Route::get('/rates/index', 'App\Http\Controllers\Rates\RatesController@index');
    Route::get('/rates/create', 'App\Http\Controllers\Rates\RatesController@create');
    Route::get('/rates/{id}/edit', 'App\Http\Controllers\Rates\RatesController@edit');
    Route::match(['put', 'patch'], '/rates/{id}/update', 'App\Http\Controllers\Rates\RatesController@update');
    Route::post('/rates/store', 'App\Http\Controllers\Rates\RatesController@store');
    Route::delete('/rates/{id}/delete', 'App\Http\Controllers\Rates\RatesController@destroy');

    // Funeral exception routes
    Route::get('/rates/exceptions/index', 'App\Http\Controllers\Rates\FuneralExceptionsController@index');
    Route::get('/rates/exceptions/create', 'App\Http\Controllers\Rates\FuneralExceptionsController@create');
    Route::get('/rates/exceptions/{id}/edit', 'App\Http\Controllers\Rates\FuneralExceptionsController@edit');
    Route::match(
        ['put', 'patch'],
        '/rates/exceptions/{id}/update',
        'App\Http\Controllers\Rates\FuneralExceptionsController@update'
    );
    Route::post('/rates/exceptions/store', 'App\Http\Controllers\Rates\FuneralExceptionsController@store');
    Route::delete('/rates/exceptions/{id}/delete', 'App\Http\Controllers\Rates\FuneralExceptionsController@destroy');

    // Expenses routes
    Route::get('/expenses/index', 'App\Http\Controllers\ExpensesController@index');
    Route::get('/expenses/{year}/data', 'App\Http\Controllers\ExpensesController@data');
    Route::get('/expenses/create', 'App\Http\Controllers\ExpensesController@create');
    Route::get('/expenses/{id}/edit', 'App\Http\Controllers\ExpensesController@edit');
    Route::post('/expenses/store', 'App\Http\Controllers\ExpensesController@store');
    Route::match(['put', 'patch'], '/expenses/{id}/update', 'App\Http\Controllers\ExpensesController@update');
    Route::delete('/expenses/{id}/delete', 'App\Http\Controllers\ExpensesController@destroy');

    // Offers routes
    Route::get('/offers/index', 'App\Http\Controllers\OffersController@index');
    Route::get('/offers/{year}/data', 'App\Http\Controllers\OffersController@data');
    Route::get('/offers/create', 'App\Http\Controllers\OffersController@create');
    Route::get('/offers/{id}/edit', 'App\Http\Controllers\OffersController@edit');
    Route::post('/offers/store', 'App\Http\Controllers\OffersController@store');
    Route::match(['put', 'patch'], '/offers/{id}/update', 'App\Http\Controllers\OffersController@update');
    Route::delete('/offers/{id}/delete', 'App\Http\Controllers\OffersController@destroy');

    // Deliveries routes
    Route::get('/deliveries/index', 'App\Http\Controllers\Deliveries\DeliveriesController@index');
    Route::get('/deliveries/{year}/data', 'App\Http\Controllers\Deliveries\DeliveriesController@deliveriesList');
    Route::get('/deliveries/create', 'App\Http\Controllers\Deliveries\DeliveriesController@create');
    Route::post('/deliveries/store', 'App\Http\Controllers\Deliveries\DeliveriesController@store');
    Route::delete('/deliveries/delete/{year}/last', 'App\Http\Controllers\Deliveries\DeliveriesController@deleteLast');
    Route::delete('/deliveries/delete/{year}/all', 'App\Http\Controllers\Deliveries\DeliveriesController@deleteAll');

    // Report routes
    Route::get('/report/customers/yearly/index', 'App\Http\Controllers\Report\CustomersYearlyController@index');
    Route::get(
        '/report/customers/yearly/{year}/{late}/extended/list',
        'App\Http\Controllers\Report\CustomersYearlyController@listDataExtended'
    );

    Route::get('/report/customers/new/index', 'App\Http\Controllers\Report\NewCustomersController@index');
    Route::get('/report/customers/new/{year}/list', 'App\Http\Controllers\Report\NewCustomersController@listData');

    Route::get('/report/customers/deceased/index', 'App\Http\Controllers\Report\DeceasedController@index');
    Route::get('/report/customers/deceased/{year}/list', 'App\Http\Controllers\Report\DeceasedController@listData');

    Route::get('/report/customers/revocated/index', 'App\Http\Controllers\Report\RevocatedController@index');
    Route::get('/report/customers/revocated/{year}/list', 'App\Http\Controllers\Report\RevocatedController@listData');

    Route::get('/report/customers/late/index', 'App\Http\Controllers\Report\LateController@index');
    Route::get('/report/customers/late/{year}/list', 'App\Http\Controllers\Report\LateController@listData');

    Route::get('/report/customers/age/index', 'App\Http\Controllers\Report\ListByAgeController@index');
    Route::get('/report/customers/age/data/{age}', 'App\Http\Controllers\Report\ListByAgeController@data');

    Route::get('/report/customers/estimation/index', 'App\Http\Controllers\Report\EstimationController@index');
    Route::post('/report/customers/estimation/print', 'App\Http\Controllers\Report\EstimationController@printReport');

    Route::get('/report/alternatives/index', 'App\Http\Controllers\Report\AlternativeReceiptsController@index');
    Route::get(
        '/report/alternatives/{year}/list',
        'App\Http\Controllers\Report\AlternativeReceiptsController@listData'
    );

    Route::get('/report/customers/duplicates/index', 'App\Http\Controllers\Report\DuplicatesController@index');

    Route::get('/report/statistics/index', 'App\Http\Controllers\StatisticsController@index');
    Route::get('/report/statistics/oldest', 'App\Http\Controllers\StatisticsController@listOldestCustomers');
    Route::get('/report/statistics/deceasedovertime/{begin}/{end}', 'App\Http\Controllers\StatisticsController@listDeceasedOverTime');

    // Closures routes
    Route::get('/closure/daily/index', 'App\Http\Controllers\Closure\DailyController@index');
    Route::get('/closure/daily/{year}/list', 'App\Http\Controllers\Closure\DailyController@listData');
    Route::get('/closure/yearly/index', 'App\Http\Controllers\Closure\YearlyController@index');
    Route::get('/closure/yearly/{year}/list', 'App\Http\Controllers\Closure\YearlyController@listData');

    // God routes
    Route::get('/god/normalize', 'App\Http\Controllers\GodController@normalizeNames');
    Route::get('/god/normalizeenrollyear', 'App\Http\Controllers\GodController@normalizeEnrollmentYear');
    Route::get('/god/normalizepeople', 'App\Http\Controllers\GodController@normalizeReceiptsNumPeople');
});
