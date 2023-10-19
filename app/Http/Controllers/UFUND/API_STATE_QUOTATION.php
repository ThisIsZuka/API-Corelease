<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\CustomException;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;

use App\Http\Controllers\UFUND\API_Quatation;
use App\Http\Controllers\UFUND\API_PROSPECT_CUSTOMER;
use App\Http\Controllers\UFUND\API_ADDRESS_PROSCPECT;

class API_STATE_QUOTATION extends BaseController
{

    public function State_Quotation(Request $request)
    {
        try {

            $API_Quatation = new API_Quatation;
            $API_PROSPECT_CUSTOMER = new API_PROSPECT_CUSTOMER;
            $API_ADDRESS_PROSCPECT = new API_ADDRESS_PROSCPECT;

            $Return_Quatation = $API_Quatation->New_Quatation($request);

            // dd($Return_Quatation->getData());
            if ($Return_Quatation->getData()->Code != '0000') {
                throw new CustomException(null, 0, new Exception(), $Return_Quatation->getData());
            }

            // dd($Return_Quatation->getData()->data->TAX_ID);

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
            // var_dump($Return_Quatation);
            // dd($NEW_QT);

            $Return_PROSPECT_CUS = $API_PROSPECT_CUSTOMER->NEW_PROSPECT_CUSTOMER($request);

            if ($Return_PROSPECT_CUS->getData()->Code != '0000') {
                $this->State_Quatation_Delete_Data($NEW_QT);
                throw new CustomException(null, 0, new Exception(), $Return_PROSPECT_CUS->getData());
            }


            $Return_ADDRESS_PROSCPECT = $API_ADDRESS_PROSCPECT->NEW_ADDRESS_PROSCPECT($request);

            if ($Return_ADDRESS_PROSCPECT->getData()->Code != '0000') {
                $this->State_Quatation_Delete_Data($NEW_QT);
                throw new CustomException(null, 0, new Exception(), $Return_ADDRESS_PROSCPECT->getData());
            }
            // dd($Return_PROSPECT_CUS);

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => $NEW_QT
            ));

        } catch (CustomException $e) {
            // dd($request);
            // dd($e);
            $options = $e->GetOptions();
            // dd($options->Code);
            return response()->json(array(
                'code' => $options->Code,
                'status' => $options->status,
                'message' => $options->message
            ));
        }
    }

    protected function State_Quatation_Delete_Data($req)
    {
        DB::table('QUOTATION')->where('QUOTATION_ID', '=', $req->QUOTATION_ID)->delete();

        DB::table('PROSPECT_CUSTOMER')
            ->where('QUOTATION_ID', '=', $req->QUOTATION_ID)
            ->where('PST_CUST_ID', '=', $req->PST_CUST_ID)
            ->delete();

        DB::table('ADDRESS_PROSPECT_CUSTOMER')
            ->where('QUOTATION_ID', '=', $req->QUOTATION_ID)
            ->where('PST_CUST_ID', '=', $req->PST_CUST_ID)
            ->where('ADD_CUST_ID', '=', $req->ADD_CUST_ID)
            ->delete();

        DB::table('PROSPECT_GUARANTOR')->where('QUOTATION_ID', '=', $req->QUOTATION_ID)->delete();
    }
}
