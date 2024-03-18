<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Http\Controllers\UFUND\Error_Exception;

use App\Models\APPLICATION;
use App\Models\SETUP_COMPANY_BRANCH;


class ApiNewApplication extends BaseController
{
    public $Error_Exception;
    public $DateStr;
    public $Date;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
        $this->DateStr = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
        $this->Date = Carbon::now(new DateTimeZone('Asia/Bangkok'));
    }

    public function New_Application(Request $request)
    {
        try {

            $data = $request->all();
            // dd($data);

            $APPLICATION = new APPLICATION([
                'STATUS_ID' => 2,
                'APPLICATION_NUMBER' => null,
                'APP_DATE' => $this->Date->format('Y-m-d'),
                'CUSTOMER_NAME' => $data['FIRST_NAME'] . " " . $data['LAST_NAME'],
                // 'CIF_PERSON_ID' => null,
                'PERSON_ID' => null,
                // 'JURISTIC_ID' => null,
                'PARTNER_ID' => 1024,
                'P_BRANCH_TYPE' => $data['BRANCH_TYPE'],
                'P_BRANCH_ID' => $data['BRANCH_ID'],
                'PRODUCT_ID' => null,
                // 'CHECKER_ID' => null,
                // 'CHECKER_RESULT' => null,
                // 'APPROVE_ID' => null,
                // 'SCORING' => null,
                'EMP_ID' => null, //รอบัสหาให้ก่อน
                'EMP_ID_Global' => null, //รอบัสหาให้ก่อน
                'EMP_ComCode' => 'COM7',
                'QUOTATION_ID' => $data['QUOTATION_ID'],
                'CREATE_DATE' => $this->DateStr,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => 'API',
            ]);

            $APPLICATION->save();

            // APPLICATION_NUMBER = BRANCH_CODE + YYYY(คศ) + (APP_ID + 1)
            $SETUP_COMPANY_BRANCH = SETUP_COMPANY_BRANCH::where('COMP_BRANCH_ID', $data['BRANCH_ID'])->first();
            $APPLICATION_NUMBER = $SETUP_COMPANY_BRANCH->BRANCH_CODE . '' . $this->Date->year . '' . ($APPLICATION->APP_ID + 1);

            $APPLICATION->APPLICATION_NUMBER = $APPLICATION_NUMBER;

            $APPLICATION->save();

            $request->request->add(
                [
                    'APP_ID' => $APPLICATION->APP_ID,
                ]
            );

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }
}
