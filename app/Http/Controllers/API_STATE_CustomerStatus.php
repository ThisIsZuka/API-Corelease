<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use stdClass;

use App\Http\Controllers\Error_Exception;

class API_STATE_CustomerStatus extends BaseController
{

    private $Error_Exception;

    function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    private function Regular_Exp($data)
    {
        return preg_replace('/\W+/', '', $data);
    }

    function CheckInput($data)
    {
        if( !isset($data['Qid']) && !isset($data['APP_ID']) ){
            // throw new Exception('Request Parameter');
            throw new Exception('Not found Data. Check Parameter [\'Qid\'] , [\'APP_ID\']', 1000);
        }
    }

    public function Get_CustomerStatus(Request $request)
    {
        try {
            $return_data = new \stdClass();

            $data = $request->all();
            $this->CheckInput($data);

            // $Q_id = $data['Qid'];
            // $APP_ID = $data['APP_ID'];

            if (isset($data['Qid']) && $data['Qid'] != 'null') {

                $return_data = $this->check_stateByQid($data);
                
            } else {
                // ฟังก์ชันสำหรับลูกค้ารายเก่าๆที่ไม่มีข้อมูล Quotation (ข้อมูลหาย)
                $return_data = $this->check_stateByAPP_ID($data);

            }

            return response()->json(array(
                'Code' => '0000',
                'status' => 'Success',
                'data' => $return_data
            ));

        } catch (Exception $e) {

            return $this->Error_Exception->Msg_error($e);

        }
    }


    function Check_IDQuotation($data)
    {
        $QT = DB::table('dbo.QUOTATION')
        ->select('QUOTATION_ID', 'CUSTOMER_NAME', 'STATUS_ID')
        ->where('QUOTATION_ID', $data['Qid'])
        ->count();

        if($QT == 0){
            return response()->json(array(
                'Code' => '1000',
                'status' => 'Invalid Data',
                'message' => '$e->getMessage()'
            ));
        }
    }


    function check_stateByQid($data)
    {
        $return_data = new \stdClass();

        $this->Check_IDQuotation($data);

        $object_Guarantor = $this->Check_Guarantor($data);
        // $return_data->Guarantor = $object_Guarantor;
        // dd($return_data->Guarantor);


        // wait approve
        $QT_status27 = DB::table('dbo.QUOTATION')
            ->select('QUOTATION_ID', 'CUSTOMER_NAME', 'STATUS_ID')
            ->where('QUOTATION_ID', $data['Qid'])
            ->where('STATUS_ID', '27')
            ->get();

        $check = 0;
        $check_QT_status27 = count($QT_status27);
        if ($check_QT_status27 == 0) {
            $Stepapprove = DB::table('dbo.APPLICATION')
                ->select('CHECKER_RESULT', 'CUSTOMER_NAME', 'APP_ID')
                ->where('QUOTATION_ID', $data['Qid'])
                ->orderBy('APP_ID', 'DESC')
                ->get();
            $check = count($Stepapprove);
        }


        // Get Product
        // $product_KYC = DB::table('dbo.PRODUCT')
        //     ->select('MT_CATEGORY.CATEGORY_NAME', 'MT_BRAND.BRAND_NAME', 'MT_SERIES.SERIES_NAME')
        //     ->leftJoin('MT_CATEGORY', 'PRODUCT_CATEGORY', '=', 'MT_CATEGORY.CATEGORY_ID')
        //     ->leftJoin('MT_BRAND', 'PRODUCT_BAND', '=', 'MT_BRAND.BRAND_ID')
        //     ->leftJoin('MT_SERIES', 'PRODUCT_SERIES', '=', 'MT_SERIES.SERIES_ID')
        //     ->leftJoin('ASSETS_INFORMATION' ,function ($join) {
        //         $join->on('rooms.id', '=', 'bookings.room_type_id')
        //         ->on()
        //     })
        //     ->where('QUOTATION_ID', $data['Qid'])
        //     ->orderBy('APP_ID', 'DESC')
        //     ->toSql();
        $product_KYC = DB::table('dbo.PRODUCT')
            ->select('MT_CATEGORY.CATEGORY_NAME', 'MT_BRAND.BRAND_NAME', 'SERIES_NAME')
            ->selectRaw('ASSETS_INFORMATION.MODELNUMBER as SKU')
            ->leftJoin('MT_CATEGORY', 'PRODUCT_CATEGORY', '=', 'MT_CATEGORY.CATEGORY_ID')
            ->leftJoin('MT_BRAND', 'PRODUCT_BAND', '=', 'MT_BRAND.BRAND_ID')
            ->leftJoin('MT_SERIES', 'PRODUCT_SERIES', '=', 'MT_SERIES.SERIES_ID')
            ->leftJoin('ASSETS_INFORMATION', function ($join) {
                $join->on('PRODUCT.PRODUCT_CATEGORY', '=', 'ASSETS_INFORMATION.ASSETS_CATEGORY')
                    ->on('PRODUCT.PRODUCT_TYPE', '=', 'ASSETS_INFORMATION.ASSETS_TYPE')
                    ->on('PRODUCT.PRODUCT_BAND', '=', 'ASSETS_INFORMATION.BRAND')
                    ->on('PRODUCT.PRODUCT_SERIES', '=', 'ASSETS_INFORMATION.SERIES')
                    ->on('PRODUCT.PRODUCT_SUB_SERIES', '=', 'ASSETS_INFORMATION.SUB_SERIES')
                    ->on('PRODUCT.PRODUCT_COLOR', '=', 'ASSETS_INFORMATION.COLOR');
            })
            ->where('QUOTATION_ID', '=', $data['Qid'])
            ->orderBy('APP_ID', 'desc')
            ->get();
        // $product_KYC[0]->SKU = preg_replace('/\W+/', '', $product_KYC[0]->SKU);
        $product_KYC[0]->SKU = $this->Regular_Exp($product_KYC[0]->SKU);

        // dd($product_KYC);
        $product_Regis = DB::table('dbo.QUOTATION')
            ->select('CATEGORY_NAME', 'MT_BRAND.BRAND_NAME', 'SERIES_NAME')
            ->leftJoin('MT_CATEGORY', 'PRODUCT_CATEGORY', '=', 'MT_CATEGORY.CATEGORY_ID')
            ->leftJoin('MT_BRAND', 'PRODUCT_BAND', '=', 'MT_BRAND.BRAND_ID')
            ->leftJoin('MT_SERIES', 'PRODUCT_SERIES', '=', 'MT_SERIES.SERIES_ID')
            ->where('QUOTATION_ID', $data['Qid'])
            ->get();


        // Get APPROVE_CODE
        $APPROVE_CODE = DB::table('dbo.QUOTATION')
            ->select('APPROVE_CODE')
            ->where('QUOTATION_ID', $data['Qid'])
            ->get();
        $return_data->APPROVE_CODE = $APPROVE_CODE[0]->APPROVE_CODE;

        $Money_Down = DB::table('dbo.QUOTATION')
            ->select('DOWN_SUM_AMT')
            ->where('QUOTATION_ID', $data['Qid'])
            ->get();
        $return_data->Money_Down = $Money_Down[0]->DOWN_SUM_AMT;

        $Company = DB::table('dbo.QUOTATION')
            ->select('BRANCH_SHORT_NAME')
            ->leftJoin('SETUP_COMPANY_BRANCH', 'BRANCH_ID', '=', 'SETUP_COMPANY_BRANCH.COMP_BRANCH_ID')
            ->where('QUOTATION_ID', $data['Qid'])
            ->get();
        $return_data->Company = $Company[0]->BRANCH_SHORT_NAME;

        // GET_QR_Code
        if ($check != 0) {
            $SMS_REPAY_Down = DB::table('dbo.TTP_SMS_RESULT')
                ->select('SEQ_ID', 'REF_NO1', 'REF_NO2', 'PAY_AMT', 'MOBILE_NO', 'SEND_STATUS', 'SEND_RESULT', 'SEND_MSG', 'APP_ID', 'APPLICATION_NUMBER')
                ->leftJoin('APPLICATION', 'APPLICATION.APPLICATION_NUMBER', '=', 'TTP_SMS_RESULT.REF_NO1')
                ->where('APPLICATION.APP_ID', $Stepapprove[0]->APP_ID)
                ->get();
            // dd($SMS_REPAY_Down);
            $check_SMS_REPAY_Down = count($SMS_REPAY_Down);

            if ($check_SMS_REPAY_Down != 0) {
                $Status_Pay_Down = DB::table('dbo.REPAYMENT')
                    ->select('*')
                    ->where('APPLICATION_NUMBER', $SMS_REPAY_Down[0]->APPLICATION_NUMBER)
                    ->where('REPAY_TYPE', '1')
                    ->where('RECEIPT_NUMBER', '!=', 'null')
                    ->where('TAX_NUMBER', '!=', 'null')
                    // ->where('PAY_NAME', '!=', 'null')
                    ->get();

                $check_Status_Pay_Down = count($Status_Pay_Down);

                if ($check_Status_Pay_Down == 0) {
                    $Status_Pay_DownStep2 = DB::table('dbo.REPAYMENT')
                        ->select('*')
                        ->where('APPLICATION_NUMBER', $SMS_REPAY_Down[0]->APPLICATION_NUMBER)
                        ->where('REPAY_TYPE', '1')
                        ->where('PAY_NAME', '!=', 'null')
                        ->get();
                    // dd($Status_Pay_DownStep2);
                    $check_Status_Pay_DownStep2 = count($Status_Pay_DownStep2);

                    if ($check_Status_Pay_DownStep2 == 0) {
                        $return_data->QR_Down = $SMS_REPAY_Down[0];
                    }
                    // $return_data->QR_Down = $SMS_REPAY_Down[0];
                }
            }
        }


        $num_product_KYC = count($product_KYC);
        if ($num_product_KYC != 0) {
            $return_data->product = $product_KYC[0];
        } else {
            $return_data->product = $product_Regis[0];
        }

        // Get CONTRACT
        if ($check != 0) {
            $CONTRACT = DB::table('dbo.CONTRACT')
                ->select('APP_ID', 'CONTRACT_NUMBER', 'STATUS_ID')
                ->where('APP_ID', $Stepapprove[0]->APP_ID)
                ->get();
            $check_CONTRACT = count($CONTRACT);
            if ($check_CONTRACT != 0) {
                $return_data->CONTRACT = $CONTRACT[0];
            }
        }


        // Step 1
        if ($check == 0) {
            $return_data->step = 'WaitKYC';
            // return $return_data;
        }
        // Step 2
        else if ($Stepapprove[0]->CHECKER_RESULT == NULL) {
            $return_data->APP_ID = $Stepapprove[0]->APP_ID;
            $return_data->step = 'WaitApprove';
        } else {

            $return_data->APP_ID = $Stepapprove[0]->APP_ID;

            if ($Stepapprove[0]->CHECKER_RESULT == 'Approve') {

                $StepDeliver = DB::table('dbo.CONTRACT')
                    ->select('APP_ID', 'STA_NAME')
                    ->leftJoin('MT_STATUS', 'STATUS_ID', '=', 'MT_STATUS.HP_STA_ID')
                    ->where('APP_ID', $Stepapprove[0]->APP_ID)
                    ->get();

                $checkDeliver = count($StepDeliver);

                if ($checkDeliver == 0) {
                    $return_data->step = 'Approve';
                } else {
                    $return_data->step = 'Deliver';
                }
            } else if ($Stepapprove[0]->CHECKER_RESULT == 'Rework') {

                // $etc = DB::table('dbo.APPROVAL_HISTORY')
                //     ->select('APPROVAL_HISTORY.*')
                //     ->where('APPROVAL_HISTORY.APP_ID', $Stepapprove[0]->APP_ID)
                //     ->where('APPROVAL_HISTORY.STATUS_ID', '5')
                //     ->get();
                $etc = DB::table('dbo.APPROVAL_HISTORY_STATUS_DESC')
                    ->select('*')
                    ->where('APP_ID', $Stepapprove[0]->APP_ID)
                    ->orderBy('CreateDate', 'DESC')
                    ->get();
                // dd($etc);

                $return_data->etc = $etc;
                $return_data->step = 'Rework';
            } else if ($Stepapprove[0]->CHECKER_RESULT == 'Reject') {
                $return_data->step = 'Reject';
            }
        }

        return $return_data;
    }

    function check_stateByAPP_ID($data)
    {

        $return_data = new \stdClass();

        // wait approve
        $Stepapprove = DB::table('dbo.APPLICATION')
            ->select('CHECKER_RESULT', 'CUSTOMER_NAME', 'APP_ID')
            ->where('APP_ID', $data['APP_ID'])
            ->orderBy('APP_ID', 'DESC')
            ->get();
        $check = count($Stepapprove);

        // Get Product
        $product_KYC = DB::table('dbo.PRODUCT')
            ->select('CATEGORY_NAME', 'MT_BRAND.BRAND_NAME', 'SERIES_NAME')
            ->leftJoin('MT_CATEGORY', 'PRODUCT_CATEGORY', '=', 'MT_CATEGORY.CATEGORY_ID')
            ->leftJoin('MT_BRAND', 'PRODUCT_BAND', '=', 'MT_BRAND.BRAND_ID')
            ->leftJoin('MT_SERIES', 'PRODUCT_SERIES', '=', 'MT_SERIES.SERIES_ID')
            ->where('APP_ID', $data['APP_ID'])
            ->orderBy('APP_ID', 'DESC')
            ->get();

        $return_data->product = $product_KYC[0];

        // Step 1
        if ($check == 0) {
            $return_data->step = 'WaitKYC';
            // return $return_data;
        }
        // Step 2
        else if ($Stepapprove[0]->CHECKER_RESULT == NULL) {
            $return_data->APP_ID = $Stepapprove[0]->APP_ID;
            $return_data->step = 'WaitApprove';
        } else {

            $return_data->APP_ID = $Stepapprove[0]->APP_ID;

            if ($Stepapprove[0]->CHECKER_RESULT == 'Approve') {

                $StepDeliver = DB::table('dbo.CONTRACT')
                    ->select('APP_ID', 'STA_NAME')
                    ->leftJoin('MT_STATUS', 'STATUS_ID', '=', 'MT_STATUS.HP_STA_ID')
                    ->where('APP_ID', $Stepapprove[0]->APP_ID)
                    ->get();

                $checkDeliver = count($StepDeliver);

                if ($checkDeliver == 0) {
                    $return_data->step = 'Approve';
                } else {
                    $return_data->step = 'Deliver';
                }
            } else if ($Stepapprove[0]->CHECKER_RESULT == 'Rework') {
                $etc = DB::table('dbo.APPROVAL_HISTORY')
                    ->select('*')
                    ->where('APP_ID', $Stepapprove[0]->APP_ID)
                    ->where('STATUS_ID', '5')
                    ->get();

                $return_data->etc = $etc;
                $return_data->step = 'Rework';
            } else if ($Stepapprove[0]->CHECKER_RESULT == 'Reject') {
                $return_data->step = 'Reject';
            }
        }
        return $return_data;
    }

    function Check_Guarantor($data)
    {
        // Get Guarantor
        $guar_QT = DB::table('dbo.PROSPECT_GUARANTOR')
            ->select('QUOTATION.QUOTATION_ID', 'PROSPECT_GUARANTOR.ACCEPT_STATUS')
            ->leftJoin('QUOTATION', 'PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'QUOTATION.QUOTATION_ID')
            ->where('QUOTATION.STATUS_ID', '27')
            ->where('QUOTATION.FLAG_GUARANTOR', '1')
            ->where(function ($query) {
                $query->whereIn('PROSPECT_GUARANTOR.RESULT_GUARANTOR', ['WAIT', 'PASS']);
                $query->where('ACTIVE_STATUS', '1');
                $query->where(function ($query_sub) {
                    $query_sub->whereNull('ACCEPT_STATUS');
                    $query_sub->orWhere('ACCEPT_STATUS', '1');
                });
            })
            ->where('QUOTATION.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->get();

        // ผู้ค้ำไม่ยินยอม
        $guar_QT_NOT_ACCEPT = DB::table('dbo.PROSPECT_GUARANTOR')
            ->select('QUOTATION.QUOTATION_ID')
            ->leftJoin('QUOTATION', 'PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'QUOTATION.QUOTATION_ID')
            ->where('QUOTATION.STATUS_ID', '27')
            ->where('QUOTATION.FLAG_GUARANTOR', '1')
            ->where('PROSPECT_GUARANTOR.ACTIVE_STATUS', '0')
            ->where('QUOTATION.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->get();

        // ผู้ค้ำไม่ผ่าน
        $guar_APP_NOTPASS = DB::table('dbo.PROSPECT_GUARANTOR')
            ->select('QUOTATION.QUOTATION_ID')
            ->leftJoin('QUOTATION', 'PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'QUOTATION.QUOTATION_ID')
            ->leftJoin('APPLICATION', function ($join) {
                $join->on('PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'APPLICATION.QUOTATION_ID');
                $join->whereIn('APPLICATION.STATUS_ID', [1, 2, 5]);
            })
            ->where('QUOTATION.STATUS_ID', '28')
            ->where('QUOTATION.FLAG_GUARANTOR', '1')
            ->whereIn('APPLICATION.STATUS_ID', [1, 2, 5])
            ->where('PROSPECT_GUARANTOR.RESULT_GUARANTOR', 'NOTPASS')
            ->where('QUOTATION.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->get();

        $guar_Change_guarantor = DB::table('dbo.QUOTATION')
            //     ->select(DB::raw("(SELECT top 1 APP_ID from APPLICATION where quotation_ID = quotation.quotation_ID and STATUS_ID = 6 order by APP_ID desc) as APP_ID
            // , (SELECT top 1 [CHANGE_GUARANTOR] FROM [HPCOM7].[dbo].[CHECKER_GUARANTOR] where APP_ID = 
            // (SELECT top 1 APP_ID from APPLICATION where quotation_ID = quotation.quotation_ID and STATUS_ID = 6 order by APP_ID desc)) as [CHANGE_GUARANTOR]"))
            ->select(
                DB::raw("(SELECT top 1 APP_ID from APPLICATION where QUOTATION_ID = quotation.QUOTATION_ID order by APP_ID desc) as APP_ID"),
                DB::raw("(SELECT top 1 CHANGE_GUARANTOR from CHECKER_GUARANTOR where APP_ID = 
                (SELECT top 1 APP_ID from APPLICATION where QUOTATION_ID = quotation.QUOTATION_ID order by APP_ID desc)) as CHANGE_GUARANTOR"),
            )
            ->where('QUOTATION.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->get();
        // dd($guar_Change_guarantor);

        $guar_APP = DB::table('dbo.PROSPECT_GUARANTOR')
            ->select('APPLICATION.APP_ID')
            ->leftJoin('QUOTATION', 'PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'QUOTATION.QUOTATION_ID')
            ->leftJoin('APPLICATION', function ($join) {
                $join->on('PROSPECT_GUARANTOR.QUOTATION_ID', '=', 'APPLICATION.QUOTATION_ID');
                // $join->on(DB::raw('APPLICATION.STATUS_ID'),[1,2]);
                $join->whereIn('APPLICATION.STATUS_ID', [1, 2, 5]);
            })
            ->where('QUOTATION.STATUS_ID', '28')
            ->where('QUOTATION.FLAG_GUARANTOR', '1')
            ->where(function ($query) {
                $query->whereIn('PROSPECT_GUARANTOR.RESULT_GUARANTOR', ['WAIT', 'PASS']);
                $query->where('ACTIVE_STATUS', '1');
                $query->where(function ($query_sub) {
                    $query_sub->whereNull('ACCEPT_STATUS');
                    $query_sub->orWhere('ACCEPT_STATUS', '1');
                });
            })
            ->whereIn('APPLICATION.STATUS_ID', [1, 2, 5])
            ->where('QUOTATION.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->get();

        $Guarantor_ID = DB::table('dbo.PROSPECT_GUARANTOR AS PG')
            ->select('PG.PST_GUAR_ID', 'QT.FLAG_GUARANTOR', 'PG.RESULT_GUARANTOR', 'PG.ACCEPT_STATUS')
            ->leftJoin('QUOTATION AS QT', 'PG.QUOTATION_ID', '=', 'QT.QUOTATION_ID')
            ->where('PG.QUOTATION_ID', isset($data['Qid']) ? $data['Qid'] : null)
            ->orderBy('PST_GUAR_ID', 'DESC')
            ->get();
        // dd($Guarantor_ID);

        $object_Guarantor = new \stdClass();
        $object_Guarantor->count = 0;
        // $object_Guarantor->PST_GUAR_ID = null;
        $object_Guarantor->QUOTATION_ID = isset($data['Qid']) ? $data['Qid'] : null;
        $object_Guarantor->QT_NOTPASS = null;
        $object_Guarantor->PST_GUAR_ID = isset($Guarantor_ID[0]->PST_GUAR_ID) ? $Guarantor_ID[0]->PST_GUAR_ID : null;
        $object_Guarantor->Guarantor_Result = isset($Guarantor_ID[0]->PST_GUAR_ID) ? $Guarantor_ID[0] : null;
        $object_Guarantor->url_accept_guarantor = 0;

        if (count($guar_QT) != 0) {
            $object_Guarantor->count = 1;
            if ($guar_QT[0]->ACCEPT_STATUS == '1') {
                $object_Guarantor->url_accept_guarantor = 1;
            }
        } elseif (count($guar_APP) != 0) {
            $object_Guarantor->count = 1;
        }

        if (count($guar_QT_NOT_ACCEPT) != 0 || count($guar_APP_NOTPASS) != 0) {
            $object_Guarantor->QT_NOTPASS = 1;
            $object_Guarantor->count = 1;
        } else if (count($guar_Change_guarantor) != 0) {
            if ($guar_Change_guarantor[0]->CHANGE_GUARANTOR == 1) {
                $object_Guarantor->QT_NOTPASS = 1;
                $object_Guarantor->count = 1;
            }
        }

        return $object_Guarantor;
    }
}
