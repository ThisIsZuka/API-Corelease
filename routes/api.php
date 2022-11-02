<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API_MT_Controller;
use App\Http\Controllers\JWT_Controller;
use Illuminate\Support\Facades\DB;


// use App\Http\Controllers\API_STATE_QUATATION;
use App\Http\Controllers\API_Quatation;
use App\Http\Controllers\API_PROSPECT_CUSTOMER;
use App\Http\Controllers\API_ADDRESS_PROSCPECT;


use App\Http\Controllers\API_CheckDown_Guarantor;
use App\Http\Controllers\API_Connect_to_D365;
use App\Http\Controllers\API_GET_ASSEST;
use App\Http\Controllers\API_GET_Warrantee;
use App\Http\Controllers\API_GET_Asset_Insurance;
use App\Http\Controllers\API_Customer_state;
use App\Http\Controllers\API_NCB_FORMATTER;
use App\Http\Controllers\D365Connect\D365Connect;
use App\Http\Controllers\test;



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

Route::group(['middleware' => ['JWT_Token']], function () {

    // Route::post('new_customer', [API_STATE_QUATATION::class, 'New_Quatation']);

    Route::post('new_customer', [API_Quatation::class, 'New_Quatation']);

    Route::post('new_prospect_cus', [API_PROSPECT_CUSTOMER::class, 'NEW_PROSPECT_CUSTOMER']);

    Route::post('new_address_prospect', [API_ADDRESS_PROSCPECT::class, 'NEW_ADDRESS_PROSCPECT']);

    Route::get('/NCBFormated/{type}', function ($type) {
        $formatter = new App\Http\Controllers\API_NCB_FORMATTER_v13;
        $result = $formatter->generate();
    
        return response()->json($result);
    });
    
    Route::post('D365GetToken', [API_Connect_to_D365::class, 'getToken']);

    Route::prefix('CallsD365')->group(function () {
        Route::post('updateNewCategory/{period}', function (Request $request, $period) {
            $D365Connector = new App\Http\Controllers\API_Connect_to_D365;
            $D365Connector->setToken($request->input('D365Token'));
            
            if ($period == 'daily') {
                $D365Connector->updateNewCategory_daily();
            }
    
            return response()->json([
                'status' => 'Completed',
                'msg' => 'Import new financial dimension to D365 Completed! please, check import log.',
                // 'Data' => $D365Connector->data,
                'Token' => $D365Connector->getToken()
            ]);
        });
        Route::post('insertNewSerial/{period}', function ($period) {
            $D365Connector = new App\Http\Controllers\API_Connect_to_D365;
            
            if ($period == 'daily') {
                $D365Connector->updateNewSerial_daily();
            }
    
            return response()->json([
                'status' => 'Completed',
                'msg' => 'Import new serial to D365 Completed! please, check import log.',
                'data' => $D365Connector->data
            ]);
        });
        Route::put('updateProductName/{period}', function ($period) {
            $D365Connector = new App\Http\Controllers\API_Connect_to_D365;
            
            if ($period == 'daily') {
                $D365Connector->updateProductName_daily();
            }
    
            return response()->json([
                'status' => 'Completed',
                'msg' => 'rename Product to D365 Completed! please, check import log.',
                'data' => $D365Connector->data
            ]);
        });
    });
});

Route::post('SKUCheckDownGua', [API_CheckDown_Guarantor::class, 'Check_Down_Guarantor']);

Route::post('SKU_ASSETS', [API_GET_ASSEST::class, 'API_GET_ASSEST']);

Route::post('SKU_Warrantee', [API_GET_Warrantee::class, 'API_GET_Warrantee']);

Route::post('SKU_ASSETS_INSURANCE', [API_GET_Asset_Insurance::class, 'API_GET_Asset_Insurance']);


Route::post('Check_Tenor', [API_CheckDown_Guarantor::class, 'Check_Tenor']);

///////////////////////////////////////////////////////////////////////////

// State Customer

Route::post('/CustomerStatus', [API_Customer_state::class, 'Get_CustomerStatus']);


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

Route::post('/generate_NCBFormat', [API_NCB_FORMATTER::class, 'generate']);

// Route::get('clear-cache', function() {
//     Artisan::call('cache:clear');
//     return "Cache is cleared";
// });