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
use App\Http\Controllers\UFUND\Error_Exception;
use App\Models\ASSETS_INFORMATION;
use stdClass;

class ApiGetAssetInsurance extends BaseController
{

    public $Error_Exception;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    public function API_GET_Asset_Insurance(Request $request)
    {
        try {

            $return_data = new stdClass;
            $data = $request->all();

            $validationRules = [
                'PRODUCT_SERIES' => 'required|integer',
            ];

            $attributeNames = [
                'PRODUCT_SERIES' => 'PRODUCT_SERIES',
            ];

            $validator = Validator::make($data, $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }


            // $product = DB::table('dbo.ASSETS_INFORMATION')
            //     ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION')
            //     ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
            //     ->where('STATUS_ID', '6')
            //     ->get();
            $product = ASSETS_INFORMATION::where('MODELNUMBER', $data['PRODUCT_SERIES'])->first();
            if (!$product) {
                throw new Exception("Not Found [PRODUCT_SERIES]", 2000);
            }

            // dd($product);

            try {
                // $check_Down = DB::select("exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
                $ASSETS_INFO = DB::select("SET NOCOUNT ON ; exec SP_GET_ASSETS_INFORMATION_REF_DETAIL @SERIES_CODE_INPUT = '" . $product->SERIES . "'  ");

                $INSURE = DB::select("SET NOCOUNT ON ; exec SP_GET_MT_INSURE_MT_SERIES_DETAIL @SERIES_ID_INPUT = '" . $product->SERIES . "'  ");


                $return_data->code = '0000';
                $return_data->status = 'Sucsess';
                $return_data->data = (array(
                    'ASSETS' => $ASSETS_INFO,
                    'INSURANCE' => $INSURE,
                ));

                return $return_data;
            } catch (Exception $e) {
                throw new Exception("Data Error. Please Check variable", 2000);
            }

        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }
}
