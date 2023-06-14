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

use App\Http\Controllers\UFUND\Check_Calculator;
use App\Http\Controllers\UFUND\Error_Exception;

class API_Quatation extends BaseController
{

    private $Error_Exception;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    public function New_Quatation(Request $request)
    {

        try {

            $data = $request->all();
            // dd($data);
            $this->validate_input($data);

            $this->Check_DupTaxID($data);

            // Check University Match Faculty
            $faculty_check = $this->Check_Uni_Fac($data);

            // Check SKU Product
            $product = $this->Check_SKU_product($data);

            // Check ACS / INSURE
            list($DB_ASC, $DB_INSURE) = $this->Check_ACS_INSURE($data);


            // Get BRANCH_AD
            $GET_BRANCH_AD = DB::table('dbo.SETUP_COMPANY_BRANCH')
                ->select('*')
                ->where('BRANCH_TYPE', $data['BRANCH_TYPE'])
                ->where('COMP_BRANCH_ID', $data['BRANCH_ID'])
                ->get();
            $BRANCH_AD = isset($GET_BRANCH_AD[0]) ? $GET_BRANCH_AD[0]->BRANCH_AD : null;

            // -----------------------------------------------------------------------------------------------------------------------------------//

            //---Calcu Prod Price
            $GET_ACS_SUM = isset($DB_ASC[0]->PRICE) ? $DB_ASC[0]->PRICE : 0;
            $GET_INSURE_SUM = isset($DB_INSURE[0]->INSURE_PRICE) ? $DB_INSURE[0]->INSURE_PRICE : 0;

            $PRD_PRICE = DB::table('dbo.ASSETS_INFORMATION')
                ->select('PRICE', 'MODELNUMBER', 'DESCRIPTION')
                // ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->where('MODELNUMBER', 'like', '%' . $data['PRODUCT_SERIES'] . '%')
                ->get();


            // $PROD_SUM_PRICE = (int)$data['PROD_SUM_PRICE'];
            $PROD_SUM_PRICE = (int)$PRD_PRICE[0]->PRICE;
            $PROD_PRICE_Float = $PROD_SUM_PRICE  * (100 / 107);
            $PROD_PRICE = round((float)$PROD_PRICE_Float, 2);
            // $PROD_VAT = $PROD_SUM_PRICE  * (7 / 107);
            $PROD_VAT = $PROD_SUM_PRICE - $PROD_PRICE;


            $DOWN_AMT_Float = (int)$data['DOWN_SUM_AMT'] * (100 / 107);
            $DOWN_AMT = round((float)$DOWN_AMT_Float, 2);
            $DOWN_VAT = $data['DOWN_SUM_AMT'] - $DOWN_AMT;


            // $all_vat = number_format((((int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM) * 7) / 107, 2, '.', '');
            // $PROD_TOTAL_AMT = (int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM;
            $PROD_TOTAL_AMT = (int)$PRD_PRICE[0]->PRICE + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM;
            $PROD_TOTAL_Float = $PROD_TOTAL_AMT  * (100 / 107);
            $PROD_TOTAL = round((float)$PROD_TOTAL_Float, 2);
            $PROD_TOTAL_VAT = $PROD_TOTAL_AMT - $PROD_TOTAL;



            $ACS_VAT_CAL = round(((int)$GET_ACS_SUM * 0.07), 2);
            $ACS_VAT = (int)$ACS_VAT_CAL == 0 ? 0 : $ACS_VAT_CAL;

            $ACS_PRICE_CAL = (int)$GET_ACS_SUM - $ACS_VAT;
            $ACS_PRICE = (int)$ACS_PRICE_CAL == 0 ? 0 : $ACS_PRICE_CAL;

            // Cal Down percent
            $DOWN_PERCENT = ($data['DOWN_SUM_AMT'] / $PROD_TOTAL_AMT);
            // dd($PROD_TOTAL_AMT);


            // Check Down Guarantor
            $check_Down = $this->Check_Guarantor($data, $product);



            // หา INTEREST_FLAT
            $BRAND = $product[0]->BRAND;
            $HP_PRODUCT_ID = DB::table('dbo.SETUP_PRODUCT_CONDITION')
                ->select('*')
                ->where('PRODUCT_BAND', $BRAND)
                ->get();


            $Get_INTEREST_FLAT = DB::table('dbo.SETUP_PRODUCT_CONDITION_DETAIL')
                ->select('*')
                // ->where('HP_PRODUCT_ID', $HP_PRODUCT_ID[0]->HP_PRODUCT_ID)
                ->limit(1)
                ->get();

            $INTEREST_FLAT = (float)$Get_INTEREST_FLAT[0]->INTEREST;
            // dd($INTEREST_FLAT);

            $HP_AMT = round(($PROD_TOTAL_AMT - (int)$data['DOWN_SUM_AMT']) / 1.07, 2);


            $INTEREST_AMT = round(($HP_AMT * $INTEREST_FLAT) * (int)$data['INSTALL_NUM'], 2);


            $HP_SUM = round($HP_AMT + $INTEREST_AMT, 2);

            $INSTALL_AMT =  round($HP_SUM / $data['INSTALL_NUM'], 2);

            $INSTALL_VAT = round(($HP_SUM / (int)$data['INSTALL_NUM']) * 7 / 100, 2);


            $INSTALL_SUM = $INSTALL_AMT + $INSTALL_VAT;

            $HP_VAT_SUM = round(($HP_AMT * 1.07) + ($INTEREST_AMT * 1.07), 2);

            // dd($HP_VAT_SUM);

            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
            $date_end = Carbon::now(new DateTimeZone('Asia/Bangkok'))->addDays(15);


            // INTEREST_EFFECTIVE
            $Check_Calculator = new Check_Calculator;
            $nper = $data['INSTALL_NUM'];
            $pmt = $INSTALL_AMT;
            $pv = - ($HP_AMT);
            // dd($HP_AMT);
            $fv = 0;
            $type = 0;
            $guess = 0.1;
            $EFFECTIVE = strval(round($Check_Calculator->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12, 8));

            // dd($EFFECTIVE);
            // var_dump(($this->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12) * 100);

            $ID_QT = DB::table('dbo.QUOTATION')->insertGetId([
                'QT_DATE' => $date_now,
                'DATE_END' => $date_end,
                'STATUS_ID' => 27,
                'APPROVE_CODE' => null,
                'BRANCH_TYPE' => $data['BRANCH_TYPE'],
                'BRANCH_ID' => $data['BRANCH_ID'],
                'BRANCH_AD' => $BRANCH_AD,
                'TAX_ID' => $data['TAX_ID'],
                'CUSTOMER_NAME' => $data['FIRST_NAME'] . ' ' . $data['LAST_NAME'],
                'OCCUPATION_ID' => $data['OCCUPATION_ID'],
                'UNIVERSITY_ID' => $data['UNIVERSITY_ID'],
                'CAMPUS_ID' => $faculty_check[0]->MT_CAMPUS_ID,
                'FACULTY_ID' => $data['FACULTY_ID'],
                'FLAG_GUARANTOR' => $check_Down[0]->Guarantor == 1 ? 1 : null,
                'PRODUCT_TYPE' => $product[0]->ASSETS_TYPE,
                'PRODUCT_CATEGORY' => $product[0]->ASSETS_CATEGORY,
                'PRODUCT_BAND' => $product[0]->BRAND,
                'PRODUCT_SERIES' => $product[0]->SERIES,
                'PRODUCT_SUB_SERIES' => $product[0]->SUB_SERIES,
                'PRODUCT_COLOR' => $product[0]->COLOR,
                'REMARK' => null,
                'PROD_PRICE' => $PROD_PRICE,
                'PROD_VAT' => $PROD_VAT,
                // 'PROD_SUM_PRICE' => $data['PROD_SUM_PRICE'],
                'PROD_SUM_PRICE' => $PRD_PRICE[0]->PRICE,
                'DOWN_PERCENT' => $DOWN_PERCENT,
                'DOWN_AMT' =>  $DOWN_AMT,
                'DOWN_VAT' => $DOWN_VAT,
                'DOWN_SUM_AMT' => $data['DOWN_SUM_AMT'],
                'HP_AMT' => $HP_AMT,
                'HP_INVEST_AMT' => $HP_AMT,
                'INTEREST_FLAT' => $INTEREST_FLAT,
                'INTEREST_EFFECTIVE' => $EFFECTIVE,
                'INSTALL_NUM' => $data['INSTALL_NUM'],
                'INTEREST_AMT' => $INTEREST_AMT,
                'HP_SUM' => $HP_SUM,
                'INSTALL_NUM_FINAL' => (int)$data['INSTALL_NUM'] - 1,
                'INSTALL_AMT' => $INSTALL_AMT,
                'INSTALL_AMT_FINAL' => $INSTALL_AMT,
                'INSTALL_VAT' => $INSTALL_VAT,
                'INSTALL_VAT_FINAL' => $INSTALL_VAT,
                'INSTALL_SUM' => $INSTALL_SUM,
                'INSTALL_SUM_FINAL' => $INSTALL_SUM,
                'CREDIT_LIMIT' => $PROD_TOTAL_AMT,
                'HP_VAT_SUM' => $HP_VAT_SUM,
                'PAY_DOWN_TYPE' => 1,
                'DESCRIPTION' => null,
                'ACS_ID' => isset($data['ACS_ID']) ? $data['ACS_ID'] : null,
                'ACS_DES' => isset($DB_ASC[0]->DESCRIPTION) ? $DB_ASC[0]->DESCRIPTION : null,
                'ACS_PRICE' => $ACS_PRICE,
                'ACS_VAT' => $ACS_VAT,
                'ACS_SUM' => isset($DB_ASC[0]->PRICE) ? $DB_ASC[0]->PRICE : 0,
                'INSURE_ID' => isset($data['INSURE_ID']) ? $data['INSURE_ID'] : null,
                'INSURE_DES' => isset($DB_INSURE[0]->INSURE_PRODUCT_NAME) ? $DB_INSURE[0]->INSURE_PRODUCT_NAME : null,
                'INSURE_SUM' => isset($DB_INSURE[0]->INSURE_PRICE) ? $DB_INSURE[0]->INSURE_PRICE : null,
                'PROD_TOTAL' => $PROD_TOTAL,
                'PROD_TOTAL_VAT' => $PROD_TOTAL_VAT,
                'PROD_TOTAL_AMT' => $PROD_TOTAL_AMT,
                'Tradein_AMT' => null,
                'CREATE_DATE' => $date_now,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => 'API',
                'DEFAULT_DOWN_PERCENT' => number_format((float)$check_Down[0]->{'@DownAMT_PERCENT_OUTPUT'}, 2, '.', ''),
            ]);

            // dd($ID_QT+100000);
            DB::table('dbo.QUOTATION')
                ->where('QUOTATION_ID',  $ID_QT)
                ->update([
                    'APPROVE_CODE' => $ID_QT + 100000,
                ]);


            $PST_CUST_ID = DB::table('dbo.PROSPECT_CUSTOMER')->insertGetId([
                'QUOTATION_ID' => $ID_QT,
                'TAX_ID' =>  $data['TAX_ID'],
            ]);

            $ADD_CUST_ID = DB::table('dbo.ADDRESS_PROSPECT_CUSTOMER')->insertGetId([
                'QUOTATION_ID' => $ID_QT,
                'PST_CUST_ID' =>  $PST_CUST_ID,
            ]);


            if ($check_Down[0]->Guarantor == 1) {
                $PST_GUAR_ID = DB::table('dbo.PROSPECT_GUARANTOR')->insertGetId([
                    'QUOTATION_ID' => $ID_QT,
                    'CREATE_DATE' => $date_now,
                ]);
            }

            return response()->json(array(
                'Code' => '0000',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $data['TAX_ID'],
                    'QUOTATION_ID' => $ID_QT,
                    'PST_CUST_ID' => $PST_CUST_ID,
                    'ADD_CUST_ID' => $ADD_CUST_ID,
                    'RequestGUARANTOR' => $check_Down[0]->Guarantor == '1' ? '1' : '0',
                    'PST_GUAR_ID' => isset($PST_GUAR_ID) ? $PST_GUAR_ID : null,
                ]
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    function validate_input($data)
    {
        $validate_Quatation = [
            "BRANCH_TYPE" => [
                'message' => 'Request Parameter [BRANCH_TYPE]',
                'numeric' => true,
            ],
            "BRANCH_ID" => [
                'message' => 'Request Parameter [BRANCH_ID]',
                'numeric' => true,
            ],
            "TAX_ID" => [
                'message' => 'Request Parameter [TAX_ID]',
                'numeric' => true,
            ],
            "FIRST_NAME" => [
                'message' => 'Request Parameter [FIRST_NAME]',
                'numeric' => false,
            ],
            "LAST_NAME" => [
                'message' => 'Request Parameter [LAST_NAME]',
                'numeric' => false,
            ],
            "OCCUPATION_ID" => [
                'message' => 'Request Parameter [OCCUPATION_ID]',
                'numeric' => true,
            ],
            "UNIVERSITY_ID" => [
                'message' => 'Request Parameter [UNIVERSITY_ID]',
                'numeric' => true,
            ],
            "FACULTY_ID" => [
                'message' => 'Request Parameter [FACULTY_ID]',
                'numeric' => true,
            ],
            "PRODUCT_SERIES" => [
                'message' => 'Request Parameter [PRODUCT_SERIES]',
                'numeric' => false,
            ],
            "DOWN_SUM_AMT" => [
                'message' => 'Request Parameter [DOWN_SUM_AMT]',
                'numeric' => true,
            ],
            "INSTALL_NUM" => [
                'message' => 'Request Parameter [INSTALL_NUM]',
                'numeric' => true,
            ],
        ];


        foreach ($validate_Quatation as $key => $value) {

            if (!isset($data[$key])) {
                throw new Exception($value['message'], 1000);
            }

            if ($value['numeric'] == true) {
                if (!is_numeric($data[$key])) {
                    throw new Exception('Request Type of $(int) [' . $key . ']', 1000);
                }
            }


            if ($key == "TAX_ID" && strlen($data[$key]) != 13) {
                throw new Exception("Invalid [TAX_ID]", 1000);
            }
        }
    }


    function Check_DupTaxID($data)
    {
        $check_TAX = DB::select("exec SP_CheckDupAppContractByTAXID  @tax_id = '" . $data['TAX_ID'] . "' ");

        if (count($check_TAX) > 0) {
            throw new Exception("[TAX_ID] is already exists", 2000);
        }
    }

    function Check_Uni_Fac($data)
    {
        // Check University Match Faculty
        $faculty_check = DB::table('dbo.MT_FACULTY')
            ->select('*')
            ->where('MT_FACULTY_ID', $data['FACULTY_ID'])
            ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
            ->get();

        if (count($faculty_check) == 0) {
            throw new Exception("[FACULTY_ID] and [UNIVERSITY_ID] is not match", 2000);
        }

        return $faculty_check;
    }


    function Check_SKU_product($data)
    {
        $product = DB::table('dbo.ASSETS_INFORMATION')
            ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION', 'BRAND')
            // ->whereRaw("TRIM(ASSETS_INFORMATION.MODELNUMBER) = '".$data['PRODUCT_SERIES']."'")
            ->where('MODELNUMBER', 'like', '%' . $data['PRODUCT_SERIES'] . '%')
            ->get();
        // dd($product);

        if (count($product) == 0) {
            throw new Exception("Not Found [PRODUCT_SERIES]", 2000);
        }

        return $product;
    }

    function Check_ACS_INSURE($data)
    {
        // Check ACS
        $validate_acs = [
            "ACS_ID" => [
                'message' => 'Request Parameter [ACS_ID]',
                'numeric' => false,
            ],
        ];


        if (isset($data['ACS_ID']) && $data['ACS_ID'] != '') {
            foreach ($validate_acs as $key => $value) {
                if (!isset($data[$key])) {
                    throw new Exception($value['message'], 1000);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']', 1000);
                    }
                }
            }
        }

        $DB_ASC = DB::table('ASSETS_INFORMATION_REF')
            ->select('*')
            ->leftJoin('ASSETS_INFORMATION', 'ASSETS_INFORMATION_REF.ASSET_ID_REF', '=', 'ASSETS_INFORMATION.ASSET_ID')
            ->where('ASSETS_INFORMATION_REF.ID', '=', $data['ACS_ID'])
            ->get();
        // dd($DB_ASC);


        // Check INSURE
        $validate_insure = [
            "INSURE_ID" => [
                'message' => 'Request Parameter [INSURE_ID]',
                'numeric' => false,
            ],
        ];



        if (isset($data['INSURE_ID']) && $data['INSURE_ID'] != '') {
            foreach ($validate_insure as $key => $value) {
                if (!isset($data[$key]) || $data[$key] == null || $data[$key] == "") {
                    throw new Exception($value['message'], 1000);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']', 1000);
                    }
                }
            }
        }

        $DB_INSURE = DB::table('dbo.MT_INSURE')
            ->select('*')
            ->where('INSURE_ID', '=', $data['INSURE_ID'])
            ->get();

        return array($DB_ASC, $DB_INSURE);
    }

    function Check_Guarantor($data, $product)
    {
        // $check_Down = DB::select("SET NOCOUNT ON ; exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
        $check_Down = DB::select("SET NOCOUNT ON ; exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0'
        , @ProductTotal_INPUT = '" . $product[0]->PRICE . "', @DownAMT_OUTPUT = '0', @DownAMT_PERCENT_OUTPUT = '0' ");

        // dd($check_Down);
        if ($data['DOWN_SUM_AMT'] < $check_Down[0]->{'@DownAMT_OUTPUT'}) {
            throw new Exception('Request [DOWN_SUM_AMT] >= ' . ($check_Down[0]->{'@DownAMT_OUTPUT'}), 2000);
        }

        return $check_Down;
    }
}
