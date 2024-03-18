<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use RodionARR\PDOService;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\UFUND\Error_Exception;
use stdClass;
use App\Models\ASSETS_INFORMATION;
use App\Models\ASSETS_INFORMATION_REF;
use App\Models\MT_INSURE;
use App\Models\MT_FACULTY;
use App\Models\MT_INSTALLMENT;

class ApiCheckDownGuarantor extends BaseController
{

    function return_Error($e)
    {
        $Error_Exception = new Error_Exception();
        return $Error_Exception->Msg_error($e);
    }

    public function Check_Down_Guarantor(Request $request)
    {
        try {

            $return_data = new stdClass;
            $data = $request->all();

            $validationRules = [
                'PRODUCT_SERIES' => 'required|integer',
                'UNIVERSITY_ID' => 'required|integer',
                'FACULTY_ID' => 'required|integer',
            ];

            $attributeNames = [
                'PRODUCT_SERIES' => 'PRODUCT_SERIES',
                'UNIVERSITY_ID' => 'UNIVERSITY_ID',
                'FACULTY_ID' => 'FACULTY_ID',
            ];

            $validator = Validator::make($data, $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }


            $product = ASSETS_INFORMATION::WHERE('MODELNUMBER', $data['PRODUCT_SERIES'])->first(['ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION', 'PRICE']);

            if (!$product) {
                throw new Exception("Not Found [PRODUCT_SERIES]", 2000);
            }

            $acsId = $data['ACS_ID'] ?? null;
            $insureId = $data['INSURE_ID'] ?? null;

            $DB_ASC = ASSETS_INFORMATION_REF::with('assetsInformation')
                ->where('ID', '=', $acsId)
                ->first();

            $DB_INSURE = MT_INSURE::where('INSURE_ID', $insureId)->first();


            $GET_ACS_SUM = $DB_ASC->assetsInformation->PRICE ?? 0;

            $GET_INSURE_SUM = $DB_INSURE->INSURE_PRICE ??  0;

            $ProductTotal_INPUT = $product->PRICE + $GET_ACS_SUM + $GET_INSURE_SUM;


            // Check University Match Faculty
            $faculty_check = MT_FACULTY::where('MT_FACULTY_ID', $data['FACULTY_ID'])
                ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
                ->first();

            if (!$faculty_check) {
                throw new Exception("[FACULTY_ID] and [UNIVERSITY_ID] is not match", 2000);
            }

            try {

                // $PRD_PRICE = DB::table('dbo.ASSETS_INFORMATION')
                //     ->select('PRICE', 'MODELNUMBER', 'DESCRIPTION')
                //     ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                //     ->get();

                $installments = MT_INSTALLMENT::getInstallmentsByPrice($ProductTotal_INPUT);


                $check_Down = DB::select("SET NOCOUNT ON ; exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0'
                , @ProductTotal_INPUT = '" . $ProductTotal_INPUT . "', @DownAMT_OUTPUT = '0', @DownAMT_PERCENT_OUTPUT = '0' ");


                $responseData = new stdClass;
                $responseData->DownMin = (float)($check_Down[0]->DownMAX);
                $responseData->DownMinPrice = (float)($check_Down[0]->{'@DownAMT_OUTPUT'});
                $responseData->ProductTotal = $ProductTotal_INPUT;
                $responseData->RequestGuarantor = $check_Down[0]->Guarantor;
                $responseData->installments = $installments;

                $return_data->code = '0000';
                $return_data->status = 'Sucsess';
                $return_data->data = $responseData;

                return $return_data;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 2000);
            }
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }


    public function Check_Tenor(Request $request)
    {
        try {

            $data = $request->all();
            $return_data = new stdClass;

            $validationRules = [
                'PROD_SUM_PRICE' => 'required|integer',
            ];

            $attributeNames = [
                'PROD_SUM_PRICE' => 'PROD_SUM_PRICE',
            ];

            $validator = Validator::make($data, $validationRules, [], $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 2000);
            }


            // Check tenor
            $sumPrice = $data['PROD_SUM_PRICE'];

            $installments = MT_INSTALLMENT::getInstallmentsByPrice($sumPrice);

            $return_data->code = '0000';
            $return_data->status = 'Sucsess';
            $return_data->data = $installments;

            return $return_data;
        } catch (Exception $e) {
            return $this->return_Error($e);
        }
    }
}
