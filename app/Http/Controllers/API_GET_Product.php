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

class API_GET_Product extends BaseController
{

    public function SKU_GetProduct()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.ASSETS_INFORMATION')
                ->select('MODELNUMBER','DESCRIPTION')
                ->where('STATUS_ID', '6')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()
                // 'message' => 'ระบบเกิดข้อผิดพลาด โปรดลองอีกครั้ง'
            ));
        }
    }
}
