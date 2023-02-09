<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API_PROSPECT_CUSTOMER;
use App\Http\Controllers\Check_Calculator;

use App\Http\Controllers\Image_resize_Controller;
use App\Http\Controllers\test;
use App\Http\Controllers\AMLO_Controller;
use App\Http\Controllers\E_Tax\E_Tax_TFF;

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
    return view('NCBMonthly');
});

Route::get('test_post_api', function () {
    return view('test_api');
});

Route::post('test_new_prospect_cus', [API_PROSPECT_CUSTOMER::class, 'NEW_PROSPECT_CUSTOMER']);


Route::get('test_EF', [Check_Calculator::class, 'test_EF']);


Route::get('loop_resize', [Image_resize_Controller::class, 'Job_Resize']);


Route::get('new_resize', [Image_resize_Controller::class, 'GetImage_base64']);

Route::get('rate_limit', [Image_resize_Controller::class, 'rate_limit_test']);


Route::get('ncb_test', [test::class, 'test_ncb']);

Route::get('test_amlo', [AMLO_Controller::class, 'New_AMLO']);

Route::get('e-tax', [E_Tax_TFF::class, 'MainRequest']);