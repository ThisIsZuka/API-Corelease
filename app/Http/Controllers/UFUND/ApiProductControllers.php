<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use stdClass;
use App\Http\Controllers\UFUND\Error_Exception;

use App\Models\ASSETS_INFORMATION;
use App\Models\MT_CATEGORY;
use App\Models\MT_BRAND;
use App\Models\MT_SERIES;

class ApiProductControllers extends BaseController
{

    public $Error_Exception;

    function __construct()
    {
        $this->Error_Exception = new Error_Exception();
    }

    // หมวดสินค้า
    public function Category()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_CATEGORY::WHERE('ACTIVE_STATUS', 'T')->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    // ยี่ห้อสินค้า
    public function Brand()
    {
        try {
            $return_data = new \stdClass();

            $MT = MT_BRAND::WHERE('ACTIVE_STATUS', '1')->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    // รุ่นสินค้า
    public function Series(Request $request, $id)
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_SERIES::WHERE('BRAND_NAME', 'Apple')->WHERE('ACTIVE_STATUS', 'T')->WHERE('BRAND_ID', $id)->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }


    // ความจุ
    public function SubSerues(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->SERIES_ID;

            $MT = DB::table('dbo.MT_SUB_SERIES')
                ->select('MT_SUB_SERIES.SUB_SERIES_ID', 'MT_SUB_SERIES.SUB_SERIES_CODE', 'MT_SUB_SERIES.SUB_SERIES_NAME', 'MT_SUB_SERIES.SERIES_ID', 'MT_SUB_SERIES.SERIES_NAME', 'ASSETS_INFORMATION.PRICE', 'MT_SUB_SERIES.ACTIVE_STATUS')
                ->leftJoin('MT_SERIES', 'MT_SUB_SERIES.SERIES_ID', '=', 'MT_SERIES.SERIES_ID')
                ->leftJoin('ASSETS_INFORMATION', 'MT_SUB_SERIES.SUB_SERIES_ID', '=', 'ASSETS_INFORMATION.SUB_SERIES')
                ->where('MT_SUB_SERIES.ACTIVE_STATUS', 'T')
                ->where('MT_SUB_SERIES.SERIES_ID', $data_get)
                ->distinct('MT_SUB_SERIES.SUB_SERIES_ID')
                ->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }


    // สี
    public function Color(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->SERIES_ID;

            $MT = DB::table('dbo.MT_COLOR')
                ->select('MT_COLOR.MT_COLOR_ID', 'MT_COLOR.COLOR_NAME', 'MT_COLOR.SUB_SERIES_ID', 'MT_COLOR.SERIES_ID', 'MT_COLOR.ACTIVE_STATUS')
                ->leftJoin('MT_SERIES', 'MT_COLOR.SERIES_ID', '=', 'MT_SERIES.SERIES_ID')
                ->where('MT_COLOR.ACTIVE_STATUS', 'T')
                ->where('MT_SERIES.SERIES_ID', $data_get)
                ->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    // อุปกรณ์เสริม
    public function AssetsInformation()
    {
        try {

            $return_data = new \stdClass();

            // $MT = DB::table('dbo.ASSETS_INFORMATION')
            //     ->select('*')
            //     ->where('DESCRIPTION', 'like', '%Apple Pencil%')
            //     ->get();

            $MT = ASSETS_INFORMATION::WHERE('DESCRIPTION', 'like', '%Apple Pencil%')->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    //สินค้า ที่ละ 20
    public function Products()
    {
        try {

            $return_data = new \stdClass();

            $MT = ASSETS_INFORMATION::where('STATUS_ID', '6')->paginate(20);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    //สินค้าตาม id
    public function Product($id)
    {
        try {
            $return_data = new \stdClass();

            $MT = ASSETS_INFORMATION::where('STATUS_ID', '6')
                ->where('STATUS_ID', '6')
                ->where('MODELNUMBER', $id)
                // ->where('ASSET_ID', $id)
                ->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    //สินค้าทั้งหมด
    public function  ProductAll()
    {
        try {

            $return_data = new \stdClass();

            $MT = ASSETS_INFORMATION::where('STATUS_ID', '6')->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }
}
