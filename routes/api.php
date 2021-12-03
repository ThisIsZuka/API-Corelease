<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API_MT_Controller;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/AllMaster', [API_MT_Controller::class, 'AllMaster_Information']);

Route::get('/master_prefix', [API_MT_Controller::class, 'MT_PREFIX']);

Route::get('/master_nationality', [API_MT_Controller::class, 'MT_NATIONALITY']);

Route::get('/master_marital_status', [API_MT_Controller::class, 'MT_MARITAL_STATUS']);

Route::get('/master_occupation', [API_MT_Controller::class, 'MT_OCCUPATION']);

Route::get('/master_level_type', [API_MT_Controller::class, 'MT_LEVEL_TYPE']);

Route::get('/master_level', [API_MT_Controller::class, 'MT_LEVEL']);

Route::get('/master_rerationship_ref', [API_MT_Controller::class, 'MT_RELATIONSHIP_REF']);

Route::get('/master_branch_type', [API_MT_Controller::class, 'MT_BRANCH_TYPE']);

Route::get('/master_setup_company/{BRANCH_TYPE_ID}', [API_MT_Controller::class, 'SETUP_COMPANY_BRANCH']);

Route::get('/master_category', [API_MT_Controller::class, 'MT_CATEGORY']);

Route::get('/master_brand', [API_MT_Controller::class, 'MT_BRAND']);

Route::get('/master_series/{BRAND_ID}', [API_MT_Controller::class, 'MT_SERIES']);

Route::get('/master_sub_series/{SERIES_ID}', [API_MT_Controller::class, 'MT_SUB_SERIES']);

Route::get('/master_color/{SERIES_ID}', [API_MT_Controller::class, 'MT_COLOR']);

Route::get('/master_assets_information', [API_MT_Controller::class, 'ASSETS_INFORMATION']);

Route::get('/master_insure/{SERIES_ID}', [API_MT_Controller::class, 'INSURE']);

Route::get('/master_installment', [API_MT_Controller::class, 'MT_INSTALLMENT']);

Route::get('/master_province', [API_MT_Controller::class, 'MT_PROVINCE']);

Route::get('/master_district/{PROVINCE_ID}', [API_MT_Controller::class, 'MT_DISTRICT']);

Route::get('/master_sub_district/{DISTRICT_ID}', [API_MT_Controller::class, 'MT_SUB_DISTRICT']);


// Route::get('/master_university/{PROVINCE_ID?}', [API_MT_Controller::class, 'MT_UNIVERSITY']);

Route::get('/master_university', [API_MT_Controller::class, 'GET_MT_UNIVERSITY']);

// Route::post('/master_university', [API_MT_Controller::class, 'POST_MT_UNIVERSITY']);

Route::get('/MT_STATUS', [API_MT_Controller::class, 'GET_MT_STATUS']);