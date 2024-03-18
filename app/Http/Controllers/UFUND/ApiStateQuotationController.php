<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Controllers\UFUND\Error_Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;

use App\Http\Controllers\UFUND\ApiNewQuatation;
use App\Http\Controllers\UFUND\ApiNewProspectCus;
use App\Http\Controllers\UFUND\ApiNewAddressProspectCus;

use App\Http\Controllers\UFUND\ApiNewApplication;
use App\Http\Controllers\UFUND\ApiNewAddress;
use App\Http\Controllers\UFUND\ApiNewProduct;
use App\Http\Controllers\UFUND\ApiNewPerson;

class ApiStateQuotationController extends BaseController
{

    private $Error_Exception;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    public function State_Quotation(Request $request)
    {
        try {

            DB::beginTransaction();

            $ApiNewQuatation = new ApiNewQuatation;
            $API_PROSPECT_CUSTOMER = new ApiNewProspectCus;
            $API_ADDRESS_PROSPECT = new ApiNewAddressProspectCus;

            $Return_Quatation = $ApiNewQuatation->New_Quatation($request);

            if ($Return_Quatation->getData()->code != '0000') {
                throw new Exception($Return_Quatation->getData()->message, $Return_Quatation->getData()->code);
            }

            $NEW_QT = $Return_Quatation->getData()->data;

            $request->request->add(
                [
                    'PST_CUST_ID' => $NEW_QT->PST_CUST_ID,
                    'QUOTATION_ID' => $NEW_QT->QUOTATION_ID,
                    'ADD_CUST_ID' => $NEW_QT->ADD_CUST_ID,
                    'PST_CUST_ID' => $NEW_QT->PST_CUST_ID,
                    'RequestGUARANTOR' => $NEW_QT->RequestGUARANTOR,
                    'PST_GUAR_ID' => $NEW_QT->PST_GUAR_ID
                ]
            );


            $Return_PROSPECT_CUS = $API_PROSPECT_CUSTOMER->NEW_PROSPECT_CUSTOMER($request);
            if ($Return_PROSPECT_CUS->getData()->code != '0000') {
                throw new Exception($Return_PROSPECT_CUS->getData()->message, $Return_PROSPECT_CUS->getData()->code);
            }


            $Return_ADDRESS_PROSPECT = $API_ADDRESS_PROSPECT->NEW_ADDRESS_PROSPECT($request);
            if ($Return_ADDRESS_PROSPECT->getData()->code != '0000') {
                throw new Exception($Return_ADDRESS_PROSPECT->getData()->message, $Return_ADDRESS_PROSPECT->getData()->code);
            }


            $StateApp = $this->State_Application($request);
            if ($StateApp->getData()->code != '0000') {
                throw new Exception($StateApp->getData()->message, $StateApp->getData()->code);
            }


            // dd($Return_PROSPECT_CUS);
            dd($NEW_QT);
            // DB::commit();

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => $NEW_QT
            ));
        } catch (Exception $e) {
            DB::rollback();
            return $this->Error_Exception->Msg_error($e);
        }
    }

    public function State_Application(Request $request)
    {
        try {
            
            $ApiNewApplication = new ApiNewApplication;
            $ReturnApp = $ApiNewApplication->New_Application($request);
            if ($ReturnApp->getData()->code != '0000') {
                throw new Exception($ReturnApp->getData()->message, $ReturnApp->getData()->code);
            }


            $ApiNewAddress = new ApiNewAddress;
            $ReturnAddress = $ApiNewAddress->New_Address($request);
            if ($ReturnAddress->getData()->code != '0000') {
                throw new Exception($ReturnAddress->getData()->message, $ReturnAddress->getData()->code);
            }


            $ApiNewProduct = new ApiNewProduct;
            $ReturnProduct = $ApiNewProduct->New_Product($request);
            if ($ReturnProduct->getData()->code != '0000') {
                throw new Exception($ReturnProduct->getData()->message, $ReturnProduct->getData()->code);
            }

            $ApiNewPerson = new ApiNewPerson;
            $ReturnPerson = $ApiNewPerson->New_Person($request);
            if ($ReturnPerson->getData()->code != '0000') {
                throw new Exception($ReturnPerson->getData()->message, $ReturnPerson->getData()->code);
            }

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }
}
