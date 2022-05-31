<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API_PROSPECT_CUSTOMER;
use App\Http\Controllers\Check_Calculator;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('test_post_api', function () {
    return view('test_api');
});


Route::post('test_new_prospect_cus', [API_PROSPECT_CUSTOMER::class, 'NEW_PROSPECT_CUSTOMER']);


Route::get('test_EF', [Check_Calculator::class, 'test_EF']);