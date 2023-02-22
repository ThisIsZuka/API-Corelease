<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;


class API_MT_Controller extends BaseController
{

    function return_Error($e){
        $previous = $e->getPrevious();
            if ($previous) {
                $message = $previous->getMessage();
            } else {
                $message = $e->getMessage();
            }

            return response()->json(array(
                'Code' => '2000',
                'status' => 'Invalid condition',
                'message' => $message
                // 'message' => 'ระบบเกิดข้อผิดพลาด โปรดลองอีกครั้ง'
            ));
    }

    // คำนำหน้าชื่อ
    public function MT_PREFIX()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_PREFIX')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }

    // สัญชาติ
    public function MT_NATIONALITY()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_NATIONALITY')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }

    // สถานะสมรส
    public function MT_MARITAL_STATUS()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_MARITAL_STATUS')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // อาชีพ
    public function MT_OCCUPATION()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_OCCUPATION')
                ->select('*')
                ->where('Ocpt_name', 'like', '%นักเรียน%')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ระดับการศึกษา
    public function MT_LEVEL_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_LEVEL_TYPE')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ชั้นปี
    public function MT_LEVEL()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_LEVEL')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ความสัมพันธ์
    public function MT_RELATIONSHIP_REF()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_RELATIONSHIP_REF')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ประเภทสาขา
    public function MT_BRANCH_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_BRANCH_TYPE')
                ->select('*')
                ->where('ACTIVE_STATUS', '=', 'T')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // สาขา
    public function SETUP_COMPANY_BRANCH(Request $request)
    {
        try {

            $return_data = new \stdClass();

            $data_get = $request->BRANCH_TYPE_ID;
            $data_search = $request->Search ?: null;
            // dd($data_search);

            $MT = DB::table('dbo.SETUP_COMPANY_BRANCH')
                ->select('COMP_BRANCH_ID', 'COMPANY_CODE', 'BRANCH_CODE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_NAME', 'BRANCH_SHORT_NAME', 'BRANCH_ADDRESS', 'PHONE_01', 'PHONE_02', 'PHONE_03', 'BRANCH_AD', 'DEP_CODE', 'BRANCH_EMAIL', 'SETUP_COMPANY_BRANCH.ACTIVE_STATUS')
                ->leftJoin('MT_BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', 'MT_BRANCH_TYPE.BRANCH_TYPE_ID')
                ->where('SETUP_COMPANY_BRANCH.ACTIVE_STATUS', '=', 'T')
                ->where('MT_BRANCH_TYPE.ACTIVE_STATUS', '=', 'T')
                ->where('SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', $data_get)
                // ->where('BRANCH_SHORT_NAME', 'LIKE', '%' . $data_search . '%')
                ->where(function ($query) use ($data_search) {
                    $query->where('BRANCH_SHORT_NAME', 'LIKE', '%' . $data_search . '%');
                    $query->orWhere('BRANCH_ADDRESS', 'LIKE', '%' . $data_search . '%');
                })
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // หมวดสินค้า
    public function MT_CATEGORY()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_CATEGORY')
                ->select('*')
                ->where('ACTIVE_STATUS', 'T')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ยี่ห้อสินค้า
    public function MT_BRAND()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_BRAND')
                ->select('*')
                ->where('ACTIVE_STATUS', '1')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // รุ่นสินค้า
    public function MT_SERIES(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->BRAND_ID;

            $MT = DB::table('dbo.MT_SERIES')
                ->select('*')
                ->where('BRAND_NAME', 'Apple')
                ->where('MT_SERIES.ACTIVE_STATUS', 'T')
                ->where('MT_SERIES.BRAND_ID', $data_get)
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ความจุ
    public function MT_SUB_SERIES(Request $request)
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

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // สี
    public function MT_COLOR(Request $request)
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

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // อุปกรณ์เสริม
    public function ASSETS_INFORMATION()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.ASSETS_INFORMATION')
                ->select('*')
                ->where('DESCRIPTION', 'like', '%Apple Pencil%')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // บริการคุ้มครองเสริม
    public function INSURE(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->SERIES_ID;

            $MT = DB::table('dbo.MT_INSURE')
                ->select('MT_INSURE.INSURE_PRODUCT_CODE', 'MT_INSURE.INSURE_PRODUCT_NAME', 'MT_INSURE.INSURE_PRICE', 'MT_INSURE.SERIES_ID', 'MT_SERIES.SERIES_NAME')
                ->leftJoin('MT_SERIES', 'MT_SERIES.SERIES_ID', '=', 'MT_INSURE.SERIES_ID')
                ->where('MT_INSURE.ACTIVE_STATUS', '=', '1')
                ->where('MT_SERIES.SERIES_ID', '=', $data_get)
                ->get();

            $return_data->Code = '0000';
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

            $MT = DB::table('dbo.MT_INSTALLMENT')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
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

            $MT = DB::table('dbo.MT_RESIDENCE_STATUS')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
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

            $MT = DB::table('dbo.MT_PROVINCE')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // อำเภอ
    public function MT_DISTRICT(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->PROVINCE_ID;

            if ($data_get == null) {
                return response()->json(array('message' => 'Sorry, wrong Data. Please try again'));
            }

            $MT = DB::table('dbo.MT_DISTRICT')
                ->select('*')
                ->leftJoin('MT_PROVINCE', 'MT_DISTRICT.PROVINCE_ID', '=', 'MT_PROVINCE.PROVINCE_ID')
                ->where('MT_DISTRICT.PROVINCE_ID', $data_get)
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // ตำบล
    public function MT_SUB_DISTRICT(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->DISTRICT_ID;

            $MT = DB::table('dbo.MT_SUB_DISTRICT')
                ->select('MT_SUB_DISTRICT.SUB_DISTRICT_ID', 'MT_SUB_DISTRICT.SUB_DISTRICT_NAME', 'MT_SUB_DISTRICT.DISTRICT_ID', 'MT_POST_CODE.POST_CODE_ID')
                ->leftJoin('MT_POST_CODE', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID', '=', 'MT_POST_CODE.SUB_DISTRICT_ID')
                ->where('DISTRICT_ID', $data_get)
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    // มหาลัย
    public function GET_MT_UNIVERSITY(Request $request)
    {
        try {
            $return_data = new \stdClass();
            $data = $request->all();
            // var_dump($data['PROVINCE_ID']);
            if (isset($data['PROVINCE_ID'])) {
                if (!preg_match('/^\d+$/', $data['PROVINCE_ID'])) {
                    $return_data->status = 'Failed';
                    $return_data->message = 'Request Type of $(int) [PROVINCE_ID]';
                } else if ($data['PROVINCE_ID'] == 10) {
                    if (isset($data['DISTRICT_ID'])) {
                        if (!preg_match('/^\d+$/', $data['DISTRICT_ID'])) {
                            $return_data->status = 'Failed';
                            $return_data->message = 'Request Type of $(int) [DISTRICT_ID]';
                            return $return_data;
                        }
                        $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                            ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID')
                            ->where('PROVINCE_ID', $data['PROVINCE_ID'])
                            ->where('DISTRICT_ID', $data['DISTRICT_ID'])
                            ->where(function ($query) use ($data) {
                                if (isset($data['U_Search'])) {
                                    $query->where('UNIVERSITY_NAME', 'LIKE', '%' . $data['U_Search'] . '%');
                                }
                            })
                            ->where('MT_UNIVERSITY_ID', '!=', '0')
                            ->get();

                        $return_data->Code = '0000';
                        $return_data->status = 'Sucsess';
                        $return_data->data = $MT;
                    } else {
                        $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                            ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID')
                            ->where('PROVINCE_ID', $data['PROVINCE_ID'])
                            ->where(function ($query) use ($data) {
                                if (isset($data['U_Search'])) {
                                    $query->where('UNIVERSITY_NAME', 'LIKE', '%' . $data['U_Search'] . '%');
                                }
                            })
                            ->where('MT_UNIVERSITY_ID', '!=', '0')
                            ->get();

                        $return_data->Code = '0000';
                        $return_data->status = 'Sucsess';
                        $return_data->data = $MT;
                    }
                } else {
                    $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                        ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID')
                        ->where('PROVINCE_ID', $data['PROVINCE_ID'])
                        ->where(function ($query) use ($data) {
                            if (isset($data['U_Search'])) {
                                $query->where('UNIVERSITY_NAME', 'LIKE', '%' . $data['U_Search'] . '%');
                            }
                        })
                        ->where('MT_UNIVERSITY_ID', '!=', '0')
                        ->get();

                    $return_data->Code = '0000';
                    $return_data->status = 'Sucsess';
                    $return_data->data = $MT;
                }
            } else {

                $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                    ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID')
                    ->where(function ($query) use ($data) {
                        if (isset($data['U_Search'])) {
                            $query->where('UNIVERSITY_NAME', 'LIKE', '%' . $data['U_Search'] . '%');
                        }
                    })
                    ->where('MT_UNIVERSITY_ID', '!=', '0')
                    // ->orderByRaw("CASE WHEN UNIVERSITY_CODE IS NULL THEN 0 ELSE 1 END DESC")
                    ->get();
                    // ->paginate(100);

                // dd($MT);

                $return_data->Code = '0000';
                $return_data->status = 'Sucsess';
                // $return_data->page = $MT->currentPage();
                // $return_data->data = $MT->items();
                $return_data->data = $MT;
            }

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

            $MT = DB::table('dbo.MT_FACULTY')
                ->select('MT_FACULTY_ID', 'FACULTY_NAME', 'MT_CAMPUS_ID', 'MT_UNIVERSITY_ID', 'UNIVERSITY_CODE')
                ->where('MT_UNIVERSITY_ID', $data_get)
                ->get();

            $return_data->Code = '0000';
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

            $MT = DB::table('dbo.MT_STATUS')
                ->select('*')
                ->get();

            $return_data->Code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }
}
