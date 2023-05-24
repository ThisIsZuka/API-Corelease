<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWT_Controller;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\UFUND\API_STATE_QUOTATION;
use App\Http\Controllers\UFUND\API_Quatation;
use App\Http\Controllers\UFUND\API_PROSPECT_CUSTOMER;
use App\Http\Controllers\UFUND\API_ADDRESS_PROSCPECT;


use App\Http\Controllers\API_CheckDown_Guarantor;
use App\Http\Controllers\API_Connect_to_D365;
use App\Http\Controllers\API_GET_ASSEST;
use App\Http\Controllers\API_GET_Warrantee;
use App\Http\Controllers\API_GET_Asset_Insurance;
use App\Http\Controllers\API_STATE_CustomerStatus;
use App\Http\Controllers\API_GET_Product;
use App\Http\Controllers\API_NCB_FORMATTER_v13;
use App\Http\Controllers\test;

use App\Http\Controllers\API_SCB_Bill_H2H;

use App\Http\Controllers\E_Tax\E_Tax_TFF;
use App\Http\Controllers\ICare\API_ICare;

use App\Http\Controllers\API_USER_Auth;


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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');

///////////////////////////////---Auth----////////////////////////////////////////////

Route::post('/Get_Token', [JWT_Controller::class, 'Get_Token']);

// Route::group(['middleware' => ['JWT_Token', 'throttle:api']], function () {
Route::group(['middleware' => ['API_CheckUser']], function () {

    // Route::post('new_customer', [API_STATE_QUOTATION::class, 'New_Quatation']);

    // Route::post('new_customer', [API_Quatation::class, 'New_Quatation']);

    // Route::post('new_prospect_cus', [API_PROSPECT_CUSTOMER::class, 'NEW_PROSPECT_CUSTOMER']);

    // Route::post('new_address_prospect', [API_ADDRESS_PROSCPECT::class, 'NEW_ADDRESS_PROSCPECT']);

    // State Quatation
    // Route::post('new_Quotation', [API_STATE_QUOTATION::class, 'State_Quotation']);

    Route::post('new_Quotation', function () {
        return abort(403);
    });
});

Route::get('SKU_GetProduct', [API_GET_Product::class, 'SKU_GetProduct']);

Route::post('SKUCheckDownGua', [API_CheckDown_Guarantor::class, 'Check_Down_Guarantor']);

Route::post('SKU_ASSETS', [API_GET_ASSEST::class, 'API_GET_ASSEST']);

Route::post('SKU_Warrantee', [API_GET_Warrantee::class, 'API_GET_Warrantee']);

Route::post('SKU_ASSETS_INSURANCE', [API_GET_Asset_Insurance::class, 'API_GET_Asset_Insurance']);


Route::post('Check_Tenor', [API_CheckDown_Guarantor::class, 'Check_Tenor']);

///////////////////////////////////////////////////////////////////////////

// State Customer
Route::group(['middleware' => ['API_CheckUser']], function () {

    Route::post('/CustomerStatus', [API_STATE_CustomerStatus::class, 'Get_CustomerStatus']);
});


///////////////////////////////////////////////////////////////////////////

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


Route::get('/master_category', [API_MT_Controller::class, 'MT_CATEGORY']);

Route::get('/master_brand', [API_MT_Controller::class, 'MT_BRAND']);

Route::get('/master_series/{BRAND_ID}', [API_MT_Controller::class, 'MT_SERIES']);

Route::get('/master_sub_series/{SERIES_ID}', [API_MT_Controller::class, 'MT_SUB_SERIES']);

Route::get('/master_color/{SERIES_ID}', [API_MT_Controller::class, 'MT_COLOR']);

Route::get('/master_assets_information', [API_MT_Controller::class, 'ASSETS_INFORMATION']);

Route::get('/master_insure/{SERIES_ID}', [API_MT_Controller::class, 'INSURE']);

Route::get('/master_installment', [API_MT_Controller::class, 'MT_INSTALLMENT']);

Route::get('/master_residence', [API_MT_Controller::class, 'MT_RESIDENCE_STATUS']);

Route::get('/master_province', [API_MT_Controller::class, 'MT_PROVINCE']);

Route::get('/master_district/{PROVINCE_ID}', [API_MT_Controller::class, 'MT_DISTRICT']);

Route::get('/master_sub_district/{DISTRICT_ID}', [API_MT_Controller::class, 'MT_SUB_DISTRICT']);



Route::get('/master_branch_type', [API_MT_Controller::class, 'MT_BRANCH_TYPE']);

Route::post('/master_setup_company/{BRANCH_TYPE_ID}', [API_MT_Controller::class, 'SETUP_COMPANY_BRANCH']);


// Route::get('/master_university/{PROVINCE_ID?}', [API_MT_Controller::class, 'MT_UNIVERSITY']);

Route::post('/master_university', [API_MT_Controller::class, 'GET_MT_UNIVERSITY']);

Route::get('/master_faculty', [API_MT_Controller::class, 'GET_MT_FACULTY']);

// Route::group(['middleware' => ['throttle:500,1'] ], function () {
//     Route::get('/master_faculty', [API_MT_Controller::class, 'GET_MT_FACULTY']);
// });


Route::get('/MT_STATUS', [API_MT_Controller::class, 'GET_MT_STATUS']);

Route::post('/Cal_EFFECTIVE', [test::class, 'Cal_EFFECTIVE']);

Route::post('/create_purcharseOrder', [API_POController::class, 'createPO']);

Route::post('/getlist/listofncbfiles', [API_NCB_FORMATTER_v13::class, 'getfiles']);

Route::post('/NCBFormated/txt/{date}', function ($date) {
    $ncbFormatted = new API_NCB_FORMATTER_v13;
    return response($ncbFormatted->generate($date));
});

Route::get('/download/ncb', function (Request $req) {
    return response()->download(public_path() . "/file_location/" . $req->get('path'));
});
// Route::get('clear-cache', function() {
//     Artisan::call('cache:clear');
//     return "Cache is cleared";
// });


// Bill Payment
Route::post('/SCBbillPayment', [API_SCB_Bill_H2H::class, 'SCB_Routing']);

// API_USER_Auth
Route::group(['middleware' => ['API_CheckUser']], function () {

    Route::post('/Create_User_API', [API_USER_Auth::class, 'CreateUser']);

    Route::post('/Update_User_API', [API_USER_Auth::class, 'UpdateUser']);
 
});


// API I-Care

// Test API
Route::get('/SP_TEST', [test::class, 'Test_API_SP']);

Route::post('e-tax', [E_Tax_TFF::class, 'MainRequest']);
Route::get('i_care', [API_ICare::class, 'NewLoan']);
