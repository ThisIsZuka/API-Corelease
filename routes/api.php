<?php

use App\Http\Controllers;
use App\Models\ContractModels;
use App\Models\CustomerModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWT_Controller;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\UFUND\ApiStateQuotationController;
use App\Http\Controllers\UFUND\ApiNewQuatation;
use App\Http\Controllers\UFUND\ApiNewProspectCus;
use App\Http\Controllers\UFUND\ApiNewAddressProspectCus;

use App\Http\Controllers\UFUND\ApiOtpControllers;
use App\Http\Controllers\UFUND\API_MT_Controller;
use App\Http\Controllers\UFUND\ApiCheckDownGuarantor;
use App\Http\Controllers\API_Connect_to_D365;
use App\Http\Controllers\UFUND\API_GET_ASSEST;
use App\Http\Controllers\UFUND\ApiGetWarrantee;
use App\Http\Controllers\UFUND\ApiGetAssetInsurance;
use App\Http\Controllers\UFUND\ApiProductControllers;
use App\Http\Controllers\UFUND\INTEREST_EFFECTIVE;
// use App\Http\Controllers\UFUND\API_STATE_CustomerStatus;
use App\Http\Controllers\UFUND\APIGetStateCustomerStatus;


use App\Http\Controllers\API_NCB_FORMATTER_v13;
use App\Http\Controllers\test;

use App\Http\Controllers\API_SCB_Bill_H2H;
use Illuminate\Support\Facades\File;

use App\Http\Controllers\E_Tax\E_Tax_TFF;
use App\Http\Controllers\line_webhook\Line;
use App\Http\Controllers\UfundCustomer\ContractInfo;
use App\Http\Controllers\UfundCustomer\Customer;
use Facade\FlareClient\Http\Response;

use App\Http\Controllers\ICare\API_ICare;

use App\Http\Controllers\API_USER_Auth;

use App\Http\Controllers\API_POController;


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

// API_Admin
Route::group(['middleware' => ['API_Admin']], function () {

    Route::post('/Create_User_API', [API_USER_Auth::class, 'CreateUser']);

    Route::post('/Update_User_API', [API_USER_Auth::class, 'UpdateUser']);
});


Route::post('/GenToken', [API_USER_Auth::class, 'generateToken']);

Route::get('/refreshToken', [API_USER_Auth::class, 'refreshToken']);

///////////////////////////////---END Auth----////////////////////////////////////////////


// API_USER_Auth

// Route::group(['middleware' => ['JWT_Token', 'throttle:api']], function () {
Route::group(['middleware' => ['JWT_Token']], function () {

    // Route::post('new_customer', [ApiStateQuotationController::class, 'New_Quatation']);

    // Route::post('new_customer', [API_Quatation::class, 'New_Quatation']);

    // Route::post('new_prospect_cus', [API_PROSPECT_CUSTOMER::class, 'NEW_PROSPECT_CUSTOMER']);

    // Route::post('new_address_prospect', [API_ADDRESS_PROSCPECT::class, 'NEW_ADDRESS_PROSCPECT']);

    // State Quatation
    Route::post('new/quotation', [ApiStateQuotationController::class, 'State_Quotation']);


    Route::get('/test_auth', [INTEREST_EFFECTIVE::class, 'CalculateEFFECTIVE']);

    ///////////////////////////////////////////////////////////////////////////

    // State Customer
    // Route::post('/CustomerStatus', [API_STATE_CustomerStatus::class, 'Get_CustomerStatus']);
    Route::post('/CustomerStatus', [APIGetStateCustomerStatus::class, 'Get_CustomerStatus']);


    ///////////////////////////////////////////////////////////////////////////

    Route::post('new_Quotation', function () {
        return 'test';
    });
});

Route::group(['middleware' => ['JWT_Token']], function () {

    Route::get('product/list', [ApiProductControllers::class, 'Products']);
    Route::get('product/all', [ApiProductControllers::class, 'ProductAll']);
    Route::get('product/{id}', [ApiProductControllers::class, 'Product']);

    Route::get('/master/category', [ApiProductControllers::class, 'Category']);
    Route::get('/master/brand', [ApiProductControllers::class, 'Brand']);
    Route::get('/master/series/{BRAND_ID}', [ApiProductControllers::class, 'Series']);
    Route::get('/master/sub/series/{SERIES_ID}', [ApiProductControllers::class, 'SubSerues']);
    Route::get('/master/color/{SERIES_ID}', [ApiProductControllers::class, 'Color']);
    Route::get('/master/assets/information', [ApiProductControllers::class, 'AssetsInformation']);

    Route::post('/sku/checkdownguarantor', [ApiCheckDownGuarantor::class, 'Check_Down_Guarantor']);

    Route::post('SKU_ASSETS', [API_GET_ASSEST::class, 'API_GET_ASSEST']);

    Route::post('SKU_Warrantee', [ApiGetWarrantee::class, 'API_GET_Warrantee']);

    Route::post('/sku/assetsinsurance', [ApiGetAssetInsurance::class, 'API_GET_Asset_Insurance']);


    Route::post('check/tenor', [ApiCheckDownGuarantor::class, 'Check_Tenor']);


    ///////////////////////////////////////////////////////////////////////////

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/AllMaster', [API_MT_Controller::class, 'AllMaster_Information']);

    Route::get('/master/prefix', [API_MT_Controller::class, 'MT_PREFIX']);

    Route::get('/master/nationality', [API_MT_Controller::class, 'MT_NATIONALITY']);

    Route::get('/master/maritalstatus', [API_MT_Controller::class, 'MT_MARITAL_STATUS']);

    Route::get('/master/occupation', [API_MT_Controller::class, 'MT_OCCUPATION']);

    Route::get('/master/level/type', [API_MT_Controller::class, 'MT_LEVEL_TYPE']);

    Route::get('/master/level', [API_MT_Controller::class, 'MT_LEVEL']);

    Route::get('/master/rerationshipref', [API_MT_Controller::class, 'MT_RELATIONSHIP_REF']);


    // Route::get('/master/assets/information', [API_MT_Controller::class, 'ASSETS_INFORMATION']);


    Route::get('/master/installment', [API_MT_Controller::class, 'MT_INSTALLMENT']);

    Route::get('/master/residence', [API_MT_Controller::class, 'MT_RESIDENCE_STATUS']);

    Route::get('/master/province', [API_MT_Controller::class, 'MT_PROVINCE']);

    Route::get('/master/district/{PROVINCE_ID}', [API_MT_Controller::class, 'MT_DISTRICT']);

    Route::get('/master/sub/district/{DISTRICT_ID}', [API_MT_Controller::class, 'MT_SUB_DISTRICT']);



    Route::get('/master/branch/type', [API_MT_Controller::class, 'MT_BRANCH_TYPE']);

    Route::post('/master/setupcompany/{BRANCH_TYPE_ID}', [API_MT_Controller::class, 'SETUP_COMPANY_BRANCH']);


    // Route::get('/master_university/{PROVINCE_ID?}', [API_MT_Controller::class, 'MT_UNIVERSITY']);

    Route::post('/master/university', [API_MT_Controller::class, 'GET_MT_UNIVERSITY']);

    Route::get('/master/faculty', [API_MT_Controller::class, 'GET_MT_FACULTY']);

    // Route::group(['middleware' => ['throttle:500,1'] ], function () {
    //     Route::get('/master_faculty', [API_MT_Controller::class, 'GET_MT_FACULTY']);
    // });

    Route::post('/sms/otp', [ApiOtpControllers::class, 'SendSMS_OTP']);
    Route::post('/used/sms/otp', [ApiOtpControllers::class, 'UsedSMS_OTP']);
    Route::post('/mail/otp', [ApiOtpControllers::class, 'SendEMail_OTP']);
    Route::post('/used/mail/otp', [ApiOtpControllers::class, 'UsedEmail_OTP']);


    Route::get('/master/status', [API_MT_Controller::class, 'GET_MT_STATUS']);

    Route::get('/master/narcotic', [API_MT_Controller::class, 'MT_Narcotic']);
    Route::get('/master/disease', [API_MT_Controller::class, 'MT_DISEASE']);
});

Route::post('/Cal_EFFECTIVE', [test::class, 'Cal_EFFECTIVE']);

Route::post('/create_purcharseOrder', [API_POController::class, 'createPO']);

Route::post('/getlist/listofncbfiles', [API_NCB_FORMATTER_v13::class, 'getfiles']);

//NCB Formatter
Route::post('/NCBFormated/txt/{date}', function ($date) {
    $ncbFormatted = new API_NCB_FORMATTER_v13;
    return response($ncbFormatted->generate($date));
});

Route::get('/pineapple/uat/UserInfo/{useremail}', function ($useremail) {
    $customer  = new Customer;
    $CustomerModels = new CustomerModels;
    return $customer->get_Customer_by_Email($useremail, $CustomerModels);
});

Route::get('/pineapple/uat/ContractInfo/{contract_id}', function ($contract_id) {
    $contract = new ContractInfo;
    $ContractModels = new ContractModels;
    return $contract->getContractInfo($contract_id, $ContractModels);
});

// Route::get('/pineapple/{useremail}', function ($useremail) {
//     return $useremail;
//     // if (isset($useremail)&&is_string($useremail)) {
//     //     $customer  = new Customer;
//     //     return $customer->get_Customer_by_Email($useremail);
//     // } else {
//     //     return response()->json('parameter Email is empty or Email is not text. please try again.');
//     // }
// });

Route::get('/download/ncb', function (Request $req) {
    return response()->download(public_path() . "/file_location/" . $req->get('path'));
});
// Route::get('clear-cache', function() {
//     Artisan::call('cache:clear');
//     return "Cache is cleared";
// });

//LINE webhook
Route::post('/line/webhook', function (Request $req) {
    File::put(public_path() . '\line_webhook_logs.txt', json_encode($req->all()));
    return response()->json(public_path() . '\line_webhook_logs.txt');
});


// Cal Rate Excel
Route::get('/CalculateEFFECTIVE', [INTEREST_EFFECTIVE::class, 'CalculateEFFECTIVE']);


// Bill Payment
Route::post('/SCBbillPayment', [API_SCB_Bill_H2H::class, 'SCB_Routing']);


// API I-Care

// Test API
Route::get('/SP_TEST', [test::class, 'Test_API_SP']);

Route::post('e-tax', [E_Tax_TFF::class, 'MainRequest']);
Route::post('test_file', [E_Tax_TFF::class, 'test_file']);
Route::post('test', function (Request $req) {
    return response()->json('hello world');
});
Route::get('i_care', [API_ICare::class, 'NewLoan']);
