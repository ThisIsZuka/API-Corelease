<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;


class API_MT_Controller extends BaseController
{
    public function AllMaster_Information()
    {
        try {
            $return_data = new \stdClass();


            $MT_PREFIX = DB::table('dbo.MT_PREFIX')
                ->select('*')
                ->get();
            $return_data->MT_PREFIX = $MT_PREFIX;


            $MT_NATIONALITY = DB::table('dbo.MT_NATIONALITY')
                ->select('*')
                ->get();
            $return_data->MT_NATIONALITY = $MT_NATIONALITY;


            $MT_MARITAL_STATUS = DB::table('dbo.MT_MARITAL_STATUS')
                ->select('*')
                ->get();
            $return_data->MT_MARITAL_STATUS = $MT_MARITAL_STATUS;


            $MT_OCCUPATION = DB::table('dbo.MT_OCCUPATION')
                ->select('*')
                ->get();
            $return_data->MT_OCCUPATION = $MT_OCCUPATION;


            $MT_LEVEL_TYPE = DB::table('dbo.MT_LEVEL_TYPE')
                ->select('*')
                ->get();
            $return_data->MT_LEVEL_TYPE = $MT_LEVEL_TYPE;


            $MT_LEVEL = DB::table('dbo.MT_LEVEL')
                ->select('*')
                ->get();
            $return_data->MT_LEVEL = $MT_LEVEL;


            return $return_data;
        } catch (Exception $e) {
            // return response()->json(array('message' => $e->getMessage()));
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_PREFIX()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_PREFIX')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function MT_NATIONALITY()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_NATIONALITY')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_MARITAL_STATUS()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_MARITAL_STATUS')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_OCCUPATION()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_OCCUPATION')
                ->select('*')
                ->where('Ocpt_name', 'like', '%นักเรียน%')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_LEVEL_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_LEVEL_TYPE')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_LEVEL()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_LEVEL')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_RELATIONSHIP_REF()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_RELATIONSHIP_REF')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function MT_BRANCH_TYPE()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_BRANCH_TYPE')
                ->select('*')
                ->where('ACTIVE_STATUS', '=', 'T')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function SETUP_COMPANY_BRANCH(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->BRANCH_TYPE_ID;

            $MT = DB::table('dbo.SETUP_COMPANY_BRANCH')
                ->select('COMP_BRANCH_ID', 'COMPANY_CODE', 'BRANCH_CODE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_NAME', 'BRANCH_SHORT_NAME', 'BRANCH_ADDRESS', 'PHONE_01', 'PHONE_02', 'PHONE_03', 'BRANCH_AD', 'DEP_CODE', 'BRANCH_EMAIL', 'SETUP_COMPANY_BRANCH.ACTIVE_STATUS')
                ->leftJoin('MT_BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', 'MT_BRANCH_TYPE.BRANCH_TYPE_ID')
                ->where('SETUP_COMPANY_BRANCH.ACTIVE_STATUS', '=', 'T')
                ->where('MT_BRANCH_TYPE.ACTIVE_STATUS', '=', 'T')
                ->where('SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', $data_get)
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function MT_CATEGORY()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_CATEGORY')
                ->select('*')
                ->where('CATEGORY_NAME', 'iPhone')
                ->orWhere('CATEGORY_NAME', 'iPad')
                ->orWhere('CATEGORY_NAME', 'MacBook')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function MT_BRAND()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_BRAND')
                ->select('*')
                ->where('BRAND_NAME', 'Apple')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

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

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


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

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

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

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;


            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    public function ASSETS_INFORMATION()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.ASSETS_INFORMATION')
                ->select('*')
                ->where('DESCRIPTION', 'like', '%Apple Pencil%')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;



            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

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

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_INSTALLMENT()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_INSTALLMENT')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_PROVINCE()
    {
        try {

            $return_data = new \stdClass();

            $MT = DB::table('dbo.MT_PROVINCE')
                ->select('*')
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


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

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function MT_SUB_DISTRICT(Request $request)
    {
        try {

            $return_data = new \stdClass();
            $data_get = $request->DISTRICT_ID;

            $MT = DB::table('dbo.MT_SUB_DISTRICT')
                ->select('*')
                ->where('DISTRICT_ID', $data_get)
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }


    // University
    public function MT_UNIVERSITY(Request $request)
    {
        try {
            // 
            $return_data = new \stdClass();

            if ($request->PROVINCE_ID == null) {
                return ('error');
            }
            $data_get = $request->PROVINCE_ID;

            // if($data_get != 10){
            //     $data_get = null;
            // }

            $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                ->select('*')
                ->where('PROVINCE_ID', $data_get)
                ->get();

            $return_data->status = 'Sucsess';
            $return_data->data = $MT;

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }

    public function POST_MT_UNIVERSITY(Request $request)
    {
        try {
            // 
            $data = $request->all();
            $return_data = new \stdClass();
            $PROVINCE_ID = null;
            $DISTRICT_ID = null;
            // dd($data);
            if ($data) {
                // return $data;
                if (isset($data['PROVINCE_ID'])) {
                    $PROVINCE_ID = $data['PROVINCE_ID'];

                    $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                        ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID', 'ZONE_ENG', 'EDU_TYPE')
                        ->where('PROVINCE_ID', $PROVINCE_ID)
                        ->get();

                    $return_data->status = 'Sucsess';
                    $return_data->data = $MT;

                    if (isset($data['DISTRICT_ID']) && $PROVINCE_ID == 10) {
                        $DISTRICT_ID = $data['DISTRICT_ID'];
                        $MT = DB::table('dbo.MT_UNIVERSITY_NAME')
                            ->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID', 'ZONE_ENG', 'EDU_TYPE')
                            ->where('PROVINCE_ID', $PROVINCE_ID)
                            ->where('DISTRICT_ID', $DISTRICT_ID)
                            ->get();

                        $return_data->status = 'Sucsess';
                        $return_data->data = $MT;
                    }
                } else {
                    $return_data->status = 'Error';
                    $return_data->message = 'required PROVINCE_ID';
                }
            } else {
                $return_data->status = 'Error';
                $return_data->message = 'required Data';
            }

            return $return_data;
        } catch (Exception $e) {
            if ($e->getPrevious()) {
                return response()->json(array(
                    'status' => 'Error',
                    'message' => $e->getPrevious()
                ));
            } else {
                return response()->json(array(
                    'status' => 'Error', 'message' => $e->getMessage()
                ));
            }
        }
    }
}
