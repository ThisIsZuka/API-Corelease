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

class API_GET_Warrantee extends BaseController
{

    public function API_GET_Warrantee(Request $request)
    {
        try {

            $return_data = new stdClass;
            $data = $request->all();

            $validate = [
                "PRODUCT_SERIES" => [
                    'message' => 'Request Parameter [PRODUCT_SERIES]',
                    // 'message' => [
                    //     'TH' => 'ข้อมูลสินค้าไม่ถูกต้อง',
                    //     'EN' => 'Product Invalid'
                    // ],
                    'numeric' => true,
                ],
            ];

            foreach ($validate as $key => $value) {
                if (!isset($data[$key])) {
                    throw new Exception($value['message'], 1000);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']', 1000);
                        // throw new Exception(json_encode($value['message']));
                    }
                }
            }


            $product = DB::table('dbo.ASSETS_INFORMATION')
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION')
                ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->get();
            if (count($product) == 0) {
                throw new Exception("Not Found [PRODUCT_SERIES]", 2000);
                // $mes_error = (object)[
                //     'TH' => 'ไม่พบข้อมูลสินค้า',
                //     'EN' => 'Not found product'
                // ];
                // throw new Exception(json_encode($mes_error));
            }

            // dd($product);

            try {
                // $check_Down = DB::select("exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
                $INSURE = DB::select("SET NOCOUNT ON ; exec SP_GET_MT_INSURE_MT_SERIES_DETAIL @SERIES_ID_INPUT = '" . $product[0]->SERIES . "'  ");
                $responseData = new stdClass;

                $return_data->code = '9999';
                $return_data->status = 'Sucsess';
                $return_data->data = $INSURE;
                // dd($return_data);

                return $return_data;
            } catch (Exception $e) {
                throw new Exception("Data invalid. Please Check variable", 2000);
                // $mes_error = (object)[
                //     'TH' => 'ข้อมูลไม่ถูกต้อง โปรดลองอีกครั้ง',
                //     'EN' => 'Data invalid. please try again'
                // ];
                // throw new Exception(json_encode($mes_error));
            }

        } catch (Exception $e) {

            $MsgError = [
                "1000" => [
                    'status' => 'Invalid Data',
                ],
                "2000" => [
                    'status' => 'Invalid Condition',
                ],
                "9000" => [
                    'status' => 'System Error',
                ],
            ];

            // dd($e);
            // $getPrevious = $e->getPrevious();
            if ($e->getPrevious() != null) {
                return response()->json(array(
                    'code' => '9000',
                    'status' =>  'System Error',
                    'message' => $e->getPrevious()->getMessage(),
                ));
            }

            return response()->json(array(
                'code' => (string)$e->getCode() ?: '1000',
                'status' =>  $MsgError[(string)$e->getCode()]['status'] ?: 'Invalid Data' ,
                'message' => $e->getMessage()
            ));

        }
    }
}
