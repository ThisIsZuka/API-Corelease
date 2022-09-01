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

class API_CheckDown_Guarantor extends BaseController
{

    public function Check_Down_Guarantor(Request $request)
    {
        try {

            $return_data = new stdClass;
            $data = $request->all();

            $validate = [
                "PRODUCT_SERIES" => [
                    'message' => 'Request Parameter [PRODUCT_SERIES]',
                    // 'message' => [
                    //     'TH' => 'ข้อมูลสินค้าไม่ถูกต้อง',
                    //     'EN' => 'Product invalid'
                    // ],
                    'numeric' => true,
                ],
                "UNIVERSITY_ID" => [
                    'message' => 'Request Parameter [UNIVERSITY_ID]',
                    // 'message' => [
                    //     'TH' => 'ข้อมูลมหาลัยไม่ถูกต้อง',
                    //     'EN' => 'University invalid'
                    // ],
                    'numeric' => true,
                ],
                "FACULTY_ID" => [
                    'message' => 'Request Parameter [FACULTY_ID]',
                    // 'message' => [
                    //     'TH' => 'ข้อมูลคณะไม่ถูกต้อง',
                    //     'EN' => 'Faculty invalid'
                    // ],
                    'numeric' => true,
                ],
            ];

            foreach ($validate as $key => $value) {
                if (!isset($data[$key])) {
                    throw new Exception($value['message']);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']');
                        // throw new Exception(json_encode($value['message']));
                    }
                }
            }


            $product = DB::table('dbo.ASSETS_INFORMATION')
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION')
                ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->get();
            if (count($product) == 0) {
                throw new Exception("Not Found [PRODUCT_SERIES]");
                // $mes_error = (object)[
                //     'TH' => 'ไม่พบข้อมูลสินค้า',
                //     'EN' => 'Not found product'
                // ];
                // throw new Exception(json_encode($mes_error));
            }

            // Check University Match Faculty
            $faculty_check = DB::table('dbo.MT_FACULTY')
                ->select('*')
                ->where('MT_FACULTY_ID', $data['FACULTY_ID'])
                ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
                ->get();
            // dd($faculty_check);
            if (count($faculty_check) == 0) {
                throw new Exception("[FACULTY_ID] and [UNIVERSITY_ID] is not match");
                // $mes_error = (object)[
                //     'TH' => 'ข้อมูลมหาวิทยาลัยและคณะไม่ถูกต้อง',
                //     'EN' => 'University and faculty is invalid'
                // ];
                // throw new Exception(json_encode($mes_error));
            }

            try {
                // $check_Down = DB::select("exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
                $check_Down = DB::select("SET NOCOUNT ON ; exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
                // dd($check_Down);
                $responseData = new stdClass;
                $responseData->DownMin = ($check_Down[0]->DownMAX);
                $responseData->RequestGuarantor = $check_Down[0]->Guarantor;

                $return_data->Code = '9999';
                $return_data->status = 'Sucsess';
                $return_data->data = $responseData;

                return $return_data;
            } catch (Exception $e) {
                throw new Exception("Data Error. Please Check variable");
                // $mes_error = (object)[
                //     'TH' => 'ข้อมูลไม่ถูกต้อง โปรดลองอีกครั้ง',
                //     'EN' => 'Data invalid. please try again'
                // ];
                // throw new Exception(json_encode($mes_error));
            }
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '0013',
                'status' => 'Error',
                'message' => $e->getMessage() 
            ));
        }
    }



    public function Check_Tenor(Request $request)
    {
        try {

            $data = $request->all();
            $return_data = new stdClass;

            $validate = [
                "PROD_SUM_PRICE" => [
                    'message' => 'Request Parameter [PROD_SUM_PRICE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลราคาสินค้าให้ถูกต้อง',
                    //     'EN' => 'Data invalid'
                    // ],
                    'numeric' => true,
                ]
            ];

            foreach ($validate as $key => $value) {
                if (!isset($data[$key])) {
                    throw new Exception($value['message']);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']');
                        // throw new Exception($value['message']);
                    }
                }
            }


            // Check tenor
            $Get_tenor = DB::table('dbo.MT_INSTALLMENT2')
                // ->select('*')
                ->select('INSTALL')
                ->where('MAX', '>=', $data['PROD_SUM_PRICE'])
                ->get();
            // dd($Get_tenor);

            $return_data->Code = '9999';
            $return_data->status = 'Sucsess';
            $return_data->data = $Get_tenor;

            return $return_data;

        } catch (Exception $e) {

            return response()->json(array(
                'Code' => '0013',
                'status' => 'Error',
                'message' => $e->getMessage()
            ));
        }
    }
}
