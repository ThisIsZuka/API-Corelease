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

use App\Models\PRODUCT;
use App\Models\ASSETS_INFORMATION;
use App\Models\ASSETS_INFORMATION_REF;
use App\Models\MT_INSURE;
use App\Models\QUOTATION;

class ApiNewProduct extends BaseController
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

    public function New_Product(Request $request)
    {
        try {

            $data = $request->all();

            $QUOTATION = QUOTATION::where('QUOTATION_ID',$data['QUOTATION_ID'])->first();

            $PRODUCT = new PRODUCT([
                // 'PRODUCT_ID' => null,
                'APP_ID' => $data['APP_ID'],
                'QUOTATION_ID' => $data['QUOTATION_ID'],
                'PRODUCT_CODE' => null,
                'PRODUCT_TYPE' => $QUOTATION->PRODUCT_TYPE,
                'PRODUCT_CATEGORY' => $QUOTATION->PRODUCT_CATEGORY,
                'PRODUCT_BAND' => $QUOTATION->PRODUCT_BAND,
                'PRODUCT_SERIES' => $QUOTATION->PRODUCT_SERIES,
                'PRODUCT_SUB_SERIES' => $QUOTATION->PRODUCT_SUB_SERIES,
                'PRODUCT_COLOR' => $QUOTATION->PRODUCT_COLOR,
                'PROD_PRICE' => $QUOTATION->PROD_PRICE,
                'PROD_VAT' => $QUOTATION->PROD_VAT,
                'PROD_SUM_PRICE' => $QUOTATION->PROD_SUM_PRICE,
                'DOWN_PERCENT' => $QUOTATION->DOWN_PERCENT,
                'DOWN_AMT' => $QUOTATION->DOWN_AMT,
                'DOWN_VAT' => $QUOTATION->DOWN_VAT,
                'DOWN_SUM_AMT' => $QUOTATION->DOWN_SUM_AMT,
                'HP_AMT' => $QUOTATION->HP_AMT,
                'HP_INVEST_AMT' => $QUOTATION->HP_INVEST_AMT,
                'INTEREST_FLAT' => $QUOTATION->INTEREST_FLAT,
                'INTEREST_EFFECTIVE' => $QUOTATION->INTEREST_EFFECTIVE,
                'INSTALL_NUM' => $QUOTATION->INSTALL_NUM,
                'INTERST_TOTAL' => $QUOTATION->INTERST_TOTAL,
                'INTEREST_VAT' => $QUOTATION->INTEREST_VAT,
                'INTEREST_AMT' => $QUOTATION->INTEREST_AMT,
                'HP_SUM' => $QUOTATION->HP_SUM,
                'INSTALL_NUM_FINAL' => $QUOTATION->INSTALL_NUM_FINAL,
                'INSTALL_AMT' => $QUOTATION->INSTALL_AMT,
                'INSTALL_AMT_FINAL' => $QUOTATION->INSTALL_AMT_FINAL,
                'INSTALL_VAT' => $QUOTATION->INSTALL_VAT,
                'INSTALL_VAT_FINAL' => $QUOTATION->INSTALL_VAT_FINAL,
                'INSTALL_SUM' => $QUOTATION->INSTALL_SUM,
                'INSTALL_SUM_FINAL' => $QUOTATION->INSTALL_SUM_FINAL,
                'HP_VAT_SUM' => $QUOTATION->HP_VAT_SUM,
                'PAY_DOWN_TYPE' => $QUOTATION->PAY_DOWN_TYPE,
                'DUEDATE_NUM' => null,
                'FRIST_PAY_DATE' => null,
                'DESCRIPTION' => null,
                'MODEL_NAME' => null,
                'MODEL_NUMBER' => null,
                'SERIAL_NUMBER' => null,
                'ABM_STARTDATE' => null,
                'ABM_ENDDATE' => null,
                'ABM_NUMBER' => null,
                'SIGNATURE_CONFIRM' => null,
                'IMAGE_DELIVER' => null,
                'CREDIT_LIMIT' => null,
                'ACS_ID' => $QUOTATION->ACS_ID,
                'ACS_DES' => $QUOTATION->ACS_DES,
                'ACS_PRICE' => $QUOTATION->ACS_PRICE,
                'ACS_VAT' => $QUOTATION->ACS_VAT,
                'ACS_SUM' => $QUOTATION->ACS_SUM,
                'INSURE_ID' => $QUOTATION->INSURE_ID,
                'INSURE_DES' => $QUOTATION->INSURE_DES,
                'INSURE_SUM' => $QUOTATION->INSURE_SUM,
                'PROD_TOTAL' => $QUOTATION->PROD_TOTAL,
                'PROD_TOTAL_VAT' => $QUOTATION->PROD_TOTAL_VAT,
                'PROD_TOTAL_AMT' => $QUOTATION->PROD_TOTAL_AMT,
                'Tradein_AMT' => null,
                'CREATE_DATE' => $this->DateStr,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => 'API',
                'DELIVERY_ID' => null,
                'REMARK' => null,
                'DEFAULT_DOWN_PERCENT' => $QUOTATION->DEFAULT_DOWN_PERCENT,
                'Icare_Des' => $QUOTATION->Icare_Des,
                'Icare_Percent' => null,
                'Icare_Price' => $QUOTATION->Icare_Price,
                'DOWN_SUM_VAT' => null,
                'DOWN_SUM_TOTAL' => null,
                'DOWN_PAY_AMT' => null,
                'AMT_PERCENT' => null,
                'TYPE_LOAN_HP' => null,
                'FEE_TOTAL_AMT' => null,
                'FEE_TOTAL' => null,
                'FEE_VAT' => null,
                'Balloon_Type' => null,
                'TRADE_IN_TYPE' => null,
                'INSTALL_NUM_BALLOON' => null,
                'TRADE_IN_INSTALL' => null,
                'PACKAGE_ID' => null,
                'PACKAGE_AMT' => null,
                'PACKAGE_VAT' => null,
                'PACKAGE_SUM_AMT' => null,
                'DISCOUNT_AMT' => null,
                'DISCOUNT_VAT' => null,
                'DISCOUNT_SUM_AMT' => null,
                'TRADE_IN_DISCOUNT_AMT' => null,
                'TRADE_IN_DISCOUNT_VAT' => null,
                'TRADE_IN_DISCOUNT_SUM_AMT' => null,
            ]);

            $PRODUCT->save();

            $request->request->add(
                [
                    'PRODUCT_ID' => $PRODUCT->PRODUCT_ID,
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
