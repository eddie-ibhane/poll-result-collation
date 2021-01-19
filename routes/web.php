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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/polling-unit-result', 'PUController@pollingUnits');
Route::get('/summed-lga-result', 'PUController@summedLGAResult' );
Route::get('/add-pu-result', 'PUController@showAddPUResultForm' );
Route::get('/ward/get/{lga_id}', 'PUController@getWards');
Route::post('/add-pu-result', 'PUController@storePUResult');


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
