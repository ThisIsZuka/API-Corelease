<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UFUND\Error_Exception;
use App\Models\MT_PREFIX;
use App\Models\MT_NATIONALITY;
use App\Models\MT_MARITAL_STATUS;
use App\Models\MT_OCCUPATION;
use App\Models\MT_LEVEL_TYPE;
use App\Models\MT_LEVEL;
use App\Models\MT_RELATIONSHIP_REF;
use App\Models\MT_BRANCH_TYPE;
use App\Models\SETUP_COMPANY_BRANCH;
use App\Models\MT_UNIVERSITY_NAME;
use App\Models\MT_FACULTY;
use App\Models\MT_STATUS;
use App\Models\MT_SERIES;
use App\Models\MT_BRAND;
use App\Models\MT_POST_CODE;
use App\Models\MT_RESIDENCE_STATUS;
use App\Models\MT_INSTALLMENT;
use App\Models\MT_PROVINCE;
use App\Models\MT_DISTRICT;
use App\Models\MT_SUB_DISTRICT;
use App\Models\MT_Narcotic;
use App\Models\MT_DISEASE;
use App\Models\ASSETS_INFORMATION;
use App\Helpers\Helper;
use Exception;
use Session;


class API_MT_Controller extends BaseController
{

    function return_Error($e)
    {
        // $previous = $e->getPrevious();
        // if ($previous) {
        //     $message = $previous->getMessage();
        // } else {
        //     $message = $e->getMessage();
        // }

        $Error_Exception = new Error_Exception();
        return $Error_Exception->Msg_error($e);

        // return response()->json(array(
        //     'code' => '2000',
        //     'status' => 'Invalid condition',
        //     'message' => $message
        //     // 'message' => 'ระบบเกิดข้อผิดพลาด โปรดลองอีกครั้ง'
        // ));
    }

    // คำนำหน้าชื่อ
    public function MT_PREFIX()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_PREFIX::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }

    // สัญชาติ
    public function MT_NATIONALITY()
    {
        try {

            $return_data = new \stdClass();

            $MT =  MT_NATIONALITY::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }

    // สถานะสมรส
    public function MT_MARITAL_STATUS()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_MARITAL_STATUS::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // อาชีพ
    public function MT_OCCUPATION()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_OCCUPATION::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // ระดับการศึกษา
    public function MT_LEVEL_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_LEVEL_TYPE::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // ชั้นปี
    public function MT_LEVEL()
    {
        try {

            $return_data = new \stdClass();

            $MT =   MT_LEVEL::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // ความสัมพันธ์
    public function MT_RELATIONSHIP_REF()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_RELATIONSHIP_REF::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // ประเภทสาขา
    public function MT_BRANCH_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_BRANCH_TYPE::WHERE('ACTIVE_STATUS', 'T')->get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // สาขา
    public function SETUP_COMPANY_BRANCH(Request $request, $id)
    {
        try {

            $return_data = new \stdClass();

            $validationRules = [
                'branch_id' => 'required|integer',
            ];

            $attributeNames = [
                'branch_id' => 'BRANCH_ID',
            ];

            $validator = Validator::make(['branch_id' => $id], $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }

            $BRANCH_TYPE_ID = $request->BRANCH_TYPE_ID;
            $TxtSearch = $request->Search ?: null;

            $MT = SETUP_COMPANY_BRANCH::getSetupCompanyBranch($BRANCH_TYPE_ID, $TxtSearch);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }

    // จำนวนงวด
    public function MT_INSTALLMENT()
    {
        try {

            $return_data = new \stdClass();

            $MT  = MT_INSTALLMENT::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // สถานะผู้อาศัย
    public function MT_RESIDENCE_STATUS()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_RESIDENCE_STATUS::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // จังหวัด
    public function MT_PROVINCE()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_PROVINCE::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // อำเภอ
    public function MT_DISTRICT(Request $request, $id)
    {
        try {

            $return_data = new \stdClass();
            // $data_get = $request->PROVINCE_ID;

            $validationRules = [
                'province_id' => 'required|integer',
            ];

            $attributeNames = [
                'province_id' => 'PROVINCE_ID',
            ];

            $validator = Validator::make(['province_id' => $id], $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }

            $MT = MT_DISTRICT::GetDistrictWithProvinceID($id);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ตำบล
    public function MT_SUB_DISTRICT(Request $request, $id)
    {
        try {

            $return_data = new \stdClass();

            $validationRules = [
                'district_id' => 'required|integer',
            ];

            $attributeNames = [
                'district_id' => 'DISTRICT_ID',
            ];

            $validator = Validator::make(['district_id' => $id], $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }

            $MT_SUB_DISTRICT = MT_SUB_DISTRICT::getSubDistrictsWithPostCode($id);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT_SUB_DISTRICT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
            // return Helper::return_Error($e);
        }
    }


    // มหาลัย
    public function GET_MT_UNIVERSITY(Request $request)
    {
        try {
            $return_data = new \stdClass();
            $data = $request->all();

            $validationRules = [
                'PROVINCE_ID' => 'nullable|integer',
                'DISTRICT_ID' => 'nullable|integer',
                'U_Search' => 'nullable|string'
            ];

            $attributeNames = [
                'PROVINCE_ID' => 'PROVINCE_ID',
                'DISTRICT_ID' => 'DISTRICT_ID',
                'U_Search' => 'U_Search'
            ];

            $validator = Validator::make($data, $validationRules, [], $attributeNames);


            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }

            $MT = MT_UNIVERSITY_NAME::searchUniversity($data);

            $return_data->code = '0000';
            $return_data->status = 'Success';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // คณะ
    public function GET_MT_FACULTY(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->MT_UNIVERSITY_ID;

            $MT = MT_FACULTY::WHERE('MT_UNIVERSITY_ID', $data_get)->get(['MT_FACULTY_ID', 'FACULTY_NAME', 'MT_CAMPUS_ID', 'MT_UNIVERSITY_ID', 'UNIVERSITY_CODE']);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    public function GET_MT_STATUS()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_STATUS::get();

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }

    
    // ประวัติการเสพสารเสพติด
    public function MT_Narcotic()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_Narcotic::get()->where('Active_Status', 1);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // โรคประจำตัว
    public function MT_DISEASE()
    {
        try {

            $return_data = new \stdClass();

            $MT = MT_DISEASE::get()->where('Active_Status', 1);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }

}
