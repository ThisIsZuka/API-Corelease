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

use App\Http\Controllers\Check_Calculator;

class API_Quatation extends BaseController
{
    public function New_Quatation(Request $request)
    {

        // $form_request = new Request([
        //     'name'   => 'unit test',
        //     'number' => 123,
        // ]);
        // $get_data = $request->all();
        // $form_request = new Request($request->all());

        // $API_PROSPECT_CUSTOMER = new API_PROSPECT_CUSTOMER;
        // $Repon = $API_PROSPECT_CUSTOMER->NEW_PROSPECT_CUSTOMER($form_request);

        try {

            $data = $request->all();
            // dd($data);

            $validate_Quatation = [
                "BRANCH_TYPE" => [
                    'message' => 'Request Parameter [BRANCH_TYPE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุประเภทสาขา',
                    //     'EN' => 'Please identify branch type'
                    // ],
                    'numeric' => true,
                ],
                "BRANCH_ID" => [
                    'message' => 'Request Parameter [BRANCH_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุสาขา',
                    //     'EN' => 'Please identify branch name'
                    // ],
                    'numeric' => true,
                ],
                "TAX_ID" => [
                    'message' => 'Request Parameter [TAX_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขบัตรประชาชน',
                    //     'EN' => 'Please identify tax ID'
                    // ],
                    'numeric' => true,
                ],
                "CUSTOMER_NAME" => [
                    'message' => 'Request Parameter [CUSTOMER_NAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุชื่อ',
                    //     'EN' => 'Please identify customer name'
                    // ],
                    'numeric' => false,
                ],
                "OCCUPATION_ID" => [
                    'message' => 'Request Parameter [OCCUPATION_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุอาชีพ',
                    //     'EN' => 'Please identify oocupation'
                    // ],
                    'numeric' => true,
                ],
                "UNIVERSITY_ID" => [
                    'message' => 'Request Parameter [UNIVERSITY_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุมหาวิทยาลัย',
                    //     'EN' => 'Please identify university'
                    // ],
                    'numeric' => true,
                ],
                "FACULTY_ID" => [
                    'message' => 'Request Parameter [FACULTY_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุคณะ',
                    //     'EN' => 'Please identify faculty'
                    // ],
                    'numeric' => true,
                ],
                "PRODUCT_SERIES" => [
                    'message' => 'Request Parameter [PRODUCT_SERIES]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุสินค้า',
                    //     'EN' => 'Please identify product'
                    // ],
                    'numeric' => false,
                ],
                "PROD_SUM_PRICE" => [
                    'message' => 'Request Parameter [PROD_SUM_PRICE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุราคาสินค้า',
                    //     'EN' => 'Request Parameter [PROD_SUM_PRICE]'
                    // ],
                    'numeric' => true,
                ],
                // "DOWN_PERCENT" => [
                //     'message' => 'Request Parameter [DOWN_PERCENT]',
                //     'numeric' => true,
                //     'percent' => true,
                // ],
                "DOWN_SUM_AMT" => [
                    'message' => 'Request Parameter [DOWN_SUM_AMT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระจำนวนเงินดาวน์',
                    //     'EN' => 'Please identify down amount'
                    // ],
                    'numeric' => true,
                ],
                "INSTALL_NUM" => [
                    'message' => 'Request Parameter [INSTALL_NUM]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระจำนวนงวดผ่อนชำระ',
                    //     'EN' => 'Please identify tenor'
                    // ],
                    'numeric' => true,
                ],
            ];

            // dd($validate);
            foreach ($validate_Quatation as $key => $value) {
                // dd($value['type']);
                // var_dump($value);
                // var_dump($data[$key]);
                if (!isset($data[$key])) {
                    throw new Exception($value['message']);
                    // throw new Exception(json_encode($value['message']));
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']');
                        // $mes_error = new stdClass;
                        // foreach ($value['message'] as $key => $value) {
                        //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    }
                }

                // dd(gettype($data['BRANCH_TYPE']));

                if ($key == "TAX_ID" && strlen($data[$key]) != 13) {
                    throw new Exception("Invalid [TAX_ID]");
                    // $mes_error = (object)[
                    //     'TH' => 'หมายเลขบัตรประชาชนต้องมี 13 หลัก',
                    //     'EN' => 'TAX ID must have 13 digits'
                    // ];
                    // throw new Exception(json_encode($mes_error));
                }

                if (isset($value['percent'])) {
                    if ($data[$key] > 1) throw new Exception('Request Parameter [' . $key . '] is 0 - 1');
                }
            }


            $check_TAX = DB::select("exec SP_CheckDupAppContractByTAXID  @tax_id = '" . $data['TAX_ID'] . "' ");
            // dd($check_TAX);
            // dd(count($check_TAX));
            if (count($check_TAX) > 0) {
                throw new Exception("[TAX_ID] is already exists");
                // $mes_error = (object)[
                //     'TH' => 'ลูกค้ามีข้อมูลอยู่ในระบบแล้ว',
                //     'EN' => 'Customer already has data in Ufund'
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
                //     'EN' => 'University and faculty is incorrect'
                // ];
                // throw new Exception(json_encode($mes_error));
            }

            // Check SKU Product
            $product = DB::table('dbo.ASSETS_INFORMATION')
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION', 'BRAND')
                ->where('MODELNUMBER', $data['PRODUCT_SERIES'])
                ->get();

            if (count($product) == 0) {
                throw new Exception("Not Found [PRODUCT_SERIES]");
                // $mes_error = (object)[
                //     'TH' => 'ไม่พบข้อมูลสินค้า',
                //     'EN' => 'Not found product.'
                // ];
                // throw new Exception(json_encode($mes_error));
            }


            // Check ACS
            $validate_acs = [
                "ACS_ID" => [
                    'message' => 'Request Parameter [ACS_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [ACS_ID]',
                    //     'EN' => 'Request Parameter [ACS_ID]'
                    // ],
                    'numeric' => false,
                ],
                // "ACS_DES" => [
                //     'message' => 'Request Parameter [ACS_DES]',
                //     'numeric' => false,
                // ],
                "ACS_SUM" => [
                    'message' => 'Request Parameter [ACS_SUM]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [ACS_SUM]',
                    //     'EN' => 'Request Parameter [ACS_SUM]'
                    // ],
                    'numeric' => true,
                ],
            ];


            if (isset($data['ACS_ID']) || isset($data['ACS_DES']) || isset($data['ACS_SUM'])) {
                foreach ($validate_acs as $key => $value) {
                    if (!isset($data[$key])) {
                        throw new Exception($value['message']);
                        // throw new Exception(json_encode($value['message']));
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . ']');
                            // throw new Exception(json_encode($value['message']));
                        }
                    }
                }
            }


            // Check INSURE
            $validate_insure = [
                "INSURE_ID" => [
                    'message' => 'Request Parameter [INSURE_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [INSURE_ID]',
                    //     'EN' => 'Request Parameter [INSURE_ID]'
                    // ],
                    'numeric' => false,
                ],
                // "INSURE_DES" => [
                //     'message' => 'Request Parameter [INSURE_DES]',
                //     'numeric' => false,
                // ],
                "INSURE_SUM" => [
                    'message' => 'Request Parameter [INSURE_SUM]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [INSURE_SUM]',
                    //     'EN' => 'Request Parameter [INSURE_SUM]'
                    // ],
                    'numeric' => true,
                ],
            ];


            if (isset($data['INSURE_ID']) || isset($data['INSURE_DES'])  || isset($data['INSURE_SUM'])) {
                foreach ($validate_insure as $key => $value) {
                    if (!isset($data[$key]) || $data[$key] == null || $data[$key] == "") {
                        throw new Exception($value['message']);
                        // throw new Exception(json_encode($value['message']));
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . ']');
                            // throw new Exception(json_encode($value['message']));
                        }
                    }
                }
            }


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
                // $mes_error = (object)[
                //     'TH' => 'กรุณาดาวน์ขั้นต่ำ '. ($check_Down[0]->DownMAX) * 100 . '%',
                //     'EN' => 'Minimum down payment '. ($check_Down[0]->DownMAX) * 100 . '%'
                // ];
                // throw new Exception(json_encode($mes_error));
            }
            // dd($check_Down);


            // Check tenor
            $Get_tenor = DB::table('dbo.MT_INSTALLMENT2')
                ->select('*')
                ->where('MAX', '>=', $PROD_TOTAL_AMT)
                ->get();
            // dd($Get_tenor);
            $toner = array();
            $check_toner = 0;
            foreach ($Get_tenor as $value) {
                if($data['INSTALL_NUM'] == $value->INSTALL){
                    $check_toner = 1;
                }
                // var_dump($value->INSTALL);
                array_push($toner, $value->INSTALL);
            }
            if($check_toner == 0){
                throw new Exception('Request [INSTALL_NUM] is '. implode(', ', $toner));
                // $mes_error = (object)[
                //     'TH' => 'จำนวนงวดที่สามารถเลือกได้คือ '. implode(', ', $toner),
                //     'EN' => 'installments that can be selected is '. implode(', ', $toner)
                // ];
                // throw new Exception(json_encode($mes_error));
            }
            // dd(array_search($data['INSTALL_NUM'], $toner));
            
           

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


            if($check_Down[0]->Guarantor == 1){
                $PST_GUAR_ID = DB::table('dbo.PROSPECT_GUARANTOR')->insertGetId([
                    'QUOTATION_ID' => $ID_QT,
                ]);
            }

            return response()->json(array(
                'Code' => '9999',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $data['TAX_ID'],
                    'QUATATION_ID' => $ID_QT,
                    'PST_CUST_ID' => $PST_CUST_ID,
                    'ADD_CUST_ID' => $ADD_CUST_ID,
                    'RequestGUARANTOR' => $check_Down[0]->Guarantor == 1 ? 1 : 0,
                    'PST_GUAR_ID' => isset($PST_GUAR_ID) ? $PST_GUAR_ID : null,
                ]
            ));
        } catch (Exception $e) {;
            // dd($e->getPrevious()->getMessage());
            // $getPrevious = $e->getPrevious();
            if ($e->getPrevious() != null) {
                return response()->json(array(
                    'Code' => '0003',
                    'status' => 'Error',
                    'message' => $e->getPrevious()->getMessage()
                    // 'message' => [
                    //     'TH' => 'ข้อมูลไม่ถูกต้อง โปรดลองอีกครั้ง',
                    //     'EN' => 'Data invalid. Please try again'
                    // ]
                ));
            }

            return response()->json(array(
                'Code' => '0013',
                'status' => 'Error',
                'message' => $e->getMessage()
            ));
        }
    }
}
