<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;


class API_STATE_QUATATION extends BaseController
{

    public function New_Quatation(Request $request)
    {
        try {

            $data = $request->all();
            // dd($data);


            // -------------------------------------------------- START Process Check Quatation ---------------------------------------------------//

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
                "CUSTOMER_NAME" => [
                    'message' => 'Request Parameter [CUSTOMER_NAME]',
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
                "PROD_SUM_PRICE" => [
                    'message' => 'Request Parameter [PROD_SUM_PRICE]',
                    'numeric' => true,
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
                    throw new Exception($value['message']);
                    // throw new Exception(json_encode($value['message']));
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']');
                    }
                }

                if ($key == "TAX_ID" && strlen($data[$key]) != 13) {
                    throw new Exception("Invalid [TAX_ID]");
                }

                // if (isset($value['percent'])) {
                //     if ($data[$key] > 1) throw new Exception('Request Parameter [' . $key . '] is 0 - 1');
                // }
            }


            $check_TAX = DB::select("exec SP_CheckDupAppContractByTAXID  @tax_id = '" . $data['TAX_ID'] . "' ");
            if (count($check_TAX) > 0) {
                throw new Exception("[TAX_ID] is already exists");
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
            }

            // Check SKU Product
            $product = DB::table('dbo.ASSETS_INFORMATION')
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION', 'BRAND')
                ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->get();

            if (count($product) == 0) {
                throw new Exception("Not Found [PRODUCT_SERIES]");
            }

            // Check ACS
            $validate_acs = [
                "ACS_ID" => [
                    'message' => 'Request Parameter [ACS_ID]',
                    'numeric' => false,
                ],
                // "ACS_DES" => [
                //     'message' => 'Request Parameter [ACS_DES]',
                //     'numeric' => false,
                // ],
                "ACS_SUM" => [
                    'message' => 'Request Parameter [ACS_SUM]',
                    'numeric' => true,
                ],
            ];


            if (isset($data['ACS_ID']) || isset($data['ACS_DES']) || isset($data['ACS_SUM'])) {
                foreach ($validate_acs as $key => $value) {
                    if (!isset($data[$key])) {
                        throw new Exception($value['message']);
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . ']');
                        }
                    }
                }
            }


            // Check INSURE
            $validate_insure = [
                "INSURE_ID" => [
                    'message' => 'Request Parameter [INSURE_ID]',
                    'numeric' => false,
                ],
                // "INSURE_DES" => [
                //     'message' => 'Request Parameter [INSURE_DES]',
                //     'numeric' => false,
                // ],
                "INSURE_SUM" => [
                    'message' => 'Request Parameter [INSURE_SUM]',
                    'numeric' => true,
                ],
            ];


            if (isset($data['INSURE_ID']) || isset($data['INSURE_DES'])  || isset($data['INSURE_SUM'])) {
                foreach ($validate_insure as $key => $value) {
                    if (!isset($data[$key]) || $data[$key] == null || $data[$key] == "") {
                        throw new Exception($value['message']);
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . ']');
                        }
                    }
                }
            }

            $faculty_check = DB::table('dbo.MT_FACULTY')
                ->select('*')
                ->where('MT_FACULTY_ID', $data['FACULTY_ID'])
                ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
                ->get();

            $product = DB::table('dbo.ASSETS_INFORMATION')
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION', 'BRAND')
                ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->get();

            // Get BRANCH_AD
            $GET_BRANCH_AD = DB::table('dbo.SETUP_COMPANY_BRANCH')
                ->select('*')
                ->where('BRANCH_TYPE', $data['BRANCH_TYPE'])
                ->where('COMP_BRANCH_ID', $data['BRANCH_ID'])
                ->get();
            $BRANCH_AD = isset($GET_BRANCH_AD[0]) ? $GET_BRANCH_AD[0]->BRANCH_AD : null;
            // dd($GET_BRANCH_AD);


            //Calcu Prod Price
            $GET_ACS_SUM = isset($data['ACS_SUM']) ? $data['ACS_SUM'] : 0;
            $GET_INSURE_SUM = isset($data['INSURE_SUM']) ? $data['INSURE_SUM'] : 0;


            $PROD_SUM_PRICE = (int)$data['PROD_SUM_PRICE'];
            $PROD_PRICE_Float = $PROD_SUM_PRICE  * (100 / 107);
            $PROD_PRICE = round((float)$PROD_PRICE_Float, 2);
            // $PROD_VAT = $PROD_SUM_PRICE  * (7 / 107);
            $PROD_VAT = $PROD_SUM_PRICE - $PROD_PRICE;


            $DOWN_AMT_Float = (int)$data['DOWN_SUM_AMT'] * (100 / 107);
            $DOWN_AMT = round((float)$DOWN_AMT_Float, 2);
            $DOWN_VAT = $data['DOWN_SUM_AMT'] - $DOWN_AMT;


            // $all_vat = number_format((((int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM) * 7) / 107, 2, '.', '');
            $PROD_TOTAL_AMT = (int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM;
            $PROD_TOTAL_Float = $PROD_TOTAL_AMT  * (100 / 107);
            $PROD_TOTAL = round((float)$PROD_TOTAL_Float, 2);
            $PROD_TOTAL_VAT = $PROD_TOTAL_AMT - $PROD_TOTAL;



            $ACS_VAT_CAL = round(((int)$GET_ACS_SUM * 0.07), 2);
            $ACS_VAT = (int)$ACS_VAT_CAL == 0 ? 0 : $ACS_VAT_CAL;

            $ACS_PRICE_CAL = (int)$GET_ACS_SUM - $ACS_VAT;
            $ACS_PRICE = (int)$ACS_PRICE_CAL == 0 ? 0 : $ACS_PRICE_CAL;

            // Cal Down percent
            $DOWN_PERCENT = ($data['DOWN_SUM_AMT'] / $PROD_TOTAL_AMT);
            // dd($DOWN_PERCENT);


            // Check Down Guarantor
            $check_Down = DB::select("SET NOCOUNT ON ; exec SP_Check_DownPercentAndGuarantor @CATE_Input = '" . $product[0]->ASSETS_CATEGORY . "' , @SERIES_Input = '" . $product[0]->SERIES . "' ,@FAC_Input = '" . $data['FACULTY_ID'] . "' , @UNI_Input = '" . $data['UNIVERSITY_ID'] . "' , @DownMAX = '0' , @Guarantor = '0' , @CheckDefault = '0' ");
            // dd($check_Down);
            if ($DOWN_PERCENT < $check_Down[0]->DownMAX) {
                throw new Exception('Request [DOWN_SUM_AMT] >= ' . ($check_Down[0]->DownMAX) * 100 . "%");
            }


            // Check tenor
            $Get_tenor = DB::table('dbo.MT_INSTALLMENT2')
                ->select('*')
                ->where('MAX', '>=', $PROD_TOTAL_AMT)
                ->get();
            // dd($Get_tenor);
            $toner = array();
            $check_toner = 0;
            foreach ($Get_tenor as $value) {
                if ($data['INSTALL_NUM'] == $value->INSTALL) {
                    $check_toner = 1;
                }
                // var_dump($value->INSTALL);
                array_push($toner, $value->INSTALL);
            }
            if ($check_toner == 0) {
                throw new Exception('Request [INSTALL_NUM] is ' . implode(', ', $toner));
            }



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


            // -------------------------------------------------- END Process Check Quatation ---------------------------------------------------//


            $Quatation = $this->Insert_Quatation(
                $data,
                $INSTALL_AMT,
                $HP_AMT,
                $BRANCH_AD,
                $faculty_check,
                $check_Down,
                $product,
                $PROD_PRICE,
                $PROD_VAT,
                $DOWN_PERCENT,
                $DOWN_AMT,
                $DOWN_VAT,
                $INTEREST_FLAT,
                $INTEREST_AMT,
                $HP_SUM,
                $INSTALL_VAT,
                $INSTALL_SUM,
                $HP_VAT_SUM,
                $ACS_PRICE,
                $ACS_VAT,
                $PROD_TOTAL,
                $PROD_TOTAL_VAT,
                $PROD_TOTAL_AMT
            );

            $return_data = new stdClass;

            $return_data->Quatation = $Quatation;

            return $return_data;
        } catch (Exception $e) {;
            // dd($e->getPrevious()->getMessage());
            // $getPrevious = $e->getPrevious();
            if ($e->getPrevious() != null) {
                return response()->json(array(
                    'Code' => '0003',
                    'status' => 'Error',
                    'message' => $e->getPrevious()->getMessage()
                ));
            }

            return response()->json(array(
                'Code' => '0013',
                'status' => 'Error',
                'message' => $e->getMessage()
            ));
        }
    }


    // -------------------------------------------------- FUNCTION Insert_Quatation ---------------------------------------------------//
    public function Insert_Quatation(
        $data,
        $INSTALL_AMT,
        $HP_AMT,
        $BRANCH_AD,
        $faculty_check,
        $check_Down,
        $product,
        $PROD_PRICE,
        $PROD_VAT,
        $DOWN_PERCENT,
        $DOWN_AMT,
        $DOWN_VAT,
        $INTEREST_FLAT,
        $INTEREST_AMT,
        $HP_SUM,
        $INSTALL_VAT,
        $INSTALL_SUM,
        $HP_VAT_SUM,
        $ACS_PRICE,
        $ACS_VAT,
        $PROD_TOTAL,
        $PROD_TOTAL_VAT,
        $PROD_TOTAL_AMT
    ) {
        // dd($HP_VAT_SUM);

        $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
        $date_end = Carbon::now(new DateTimeZone('Asia/Bangkok'))->addDays(15);
        // var_dump($date_now);
        // dd($date_end);
        // dd($datenow->format('Y-'));


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
        // dd('ะพีำ');
        $ID_QT = DB::table('dbo.QUOTATION')->insertGetId([
            'QT_DATE' => $date_now,
            'DATE_END' => $date_end,
            'STATUS_ID' => 27,
            'APPROVE_CODE' => null,
            'BRANCH_TYPE' => $data['BRANCH_TYPE'],
            'BRANCH_ID' => $data['BRANCH_ID'],
            'BRANCH_AD' => $BRANCH_AD,
            'TAX_ID' => $data['TAX_ID'],
            'CUSTOMER_NAME' => $data['CUSTOMER_NAME'],
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
            'PROD_SUM_PRICE' => $data['PROD_SUM_PRICE'],
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
            'PAY_DOWN_TYPE' => null,
            'DESCRIPTION' => null,
            'ACS_ID' => isset($data['ACS_ID']) ? $data['ACS_ID'] : null,
            'ACS_DES' => isset($data['ACS_DES']) ? $data['ACS_DES'] : null,
            'ACS_PRICE' => $ACS_PRICE,
            'ACS_VAT' => $ACS_VAT,
            'ACS_SUM' => isset($data['ACS_SUM']) ? $data['ACS_SUM'] : 0,
            'INSURE_ID' => isset($data['INSURE_ID']) ? $data['INSURE_ID'] : null,
            'INSURE_DES' => isset($data['INSURE_DES']) ? $data['INSURE_DES'] : null,
            'INSURE_SUM' => isset($data['INSURE_SUM']) ? $data['INSURE_SUM'] : null,
            'PROD_TOTAL' => $PROD_TOTAL,
            'PROD_TOTAL_VAT' => $PROD_TOTAL_VAT,
            'PROD_TOTAL_AMT' => $PROD_TOTAL_AMT,
            'Tradein_AMT' => null,
            'CREATE_DATE' => $date_now,
            'UPDATE_DATE' => null,
            'NAME_MAKE' => 'API',

        ]);

        return $ID_QT;
    }
}
