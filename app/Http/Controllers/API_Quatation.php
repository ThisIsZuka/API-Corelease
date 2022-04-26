<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\API_PROSPECT_CUSTOMER;

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

            $validate = [
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
                "PROD_PRICE" => [
                    'message' => 'Request Parameter [PROD_PRICE]',
                    'numeric' => true,
                ],
                "DOWN_PERCENT" => [
                    'message' => 'Request Parameter [DOWN_PERCENT]',
                    'numeric' => true,
                ],
            ];

            // dd($validate);
            foreach ($validate as $key => $value) {
                // dd($value['type']);
                // var_dump($value);
                // var_dump($data[$key]);
                if (!isset($data[$key])) {
                    throw new Exception($value['message']);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . ']');
                    }
                }

                // dd(gettype($data['BRANCH_TYPE']));

                if ($key == "TAX_ID" && strlen($data[$key]) != 13) {
                    throw new Exception("Invalid [TAX_ID]");
                }
            }


            $check_TAX = DB::select("exec SP_CheckDupAppContractByTAXID  @tax_id = '" . $data['TAX_ID'] . "' ");
            // dd($check_TAX);
            // dd(count($check_TAX));
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
                ->select('ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND', 'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER', 'MODELNUMBER', 'DESCRIPTION')
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
                "ACS_DES" => [
                    'message' => 'Request Parameter [ACS_DES]',
                    'numeric' => false,
                ],
                "ACS_PRICE" => [
                    'message' => 'Request Parameter [ACS_PRICE]',
                    'numeric' => true,
                ],
            ];


            if ( isset($data['ACS_ID']) || isset($data['ACS_DES']) || isset($data['ACS_SUM']) ) {
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
                "INSURE_DES" => [
                    'message' => 'Request Parameter [INSURE_DES]',
                    'numeric' => false,
                ],
                "INSURE_SUM" => [
                    'message' => 'Request Parameter [INSURE_SUM]',
                    'numeric' => true,
                ],
            ];


            if ( isset($data['INSURE_ID']) || isset($data['INSURE_DES'])  || isset($data['INSURE_SUM']) ) {
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


            // Get BRANCH_AD
            $GET_BRANCH_AD = DB::table('dbo.SETUP_COMPANY_BRANCH')
                ->select('*')
                ->where('BRANCH_TYPE', $data['BRANCH_TYPE'])
                ->where('COMP_BRANCH_ID', $data['BRANCH_ID'])
                ->get();
            $BRANCH_AD = isset($GET_BRANCH_AD[0]) ? $GET_BRANCH_AD[0]->BRANCH_AD : null;
            // dd($GET_BRANCH_AD);


            //Calcu Prod Price
            $GET_ACS_PRICE = isset($data['ACS_PRICE']) ? $data['ACS_PRICE'] : 0;
            $GET_INSURE_SUM = isset($data['INSURE_SUM']) ? $data['INSURE_SUM'] : null;

            $GET_PROD_PRICE = (int)$data['PROD_PRICE'];

            $all_vat = number_format((((int)$data['PROD_PRICE'] + (int)$GET_ACS_PRICE + (int)$GET_INSURE_SUM) * 7) / 107, 2, '.', '');
            $PROD_PRICE = number_format((int)$data['PROD_PRICE'] - $all_vat, 2, '.', '');

            $ACS_PRICE_CAL = number_format(((int)$GET_ACS_PRICE * 0.07), 2, '.', '');
            $ACS_PRICE = (int)$ACS_PRICE_CAL == 0 ? 0 : $ACS_PRICE_CAL;
            // dd('$ACS_PRICE');

            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
            $date_end = Carbon::now(new DateTimeZone('Asia/Bangkok'))->addDays(15);
            // var_dump($date_now);
            // dd($date_end);
            // dd($datenow->format('Y-'));

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
                'FLAG_GUARANTOR' => isset($data['FLAG_GUARANTOR']) ? $data['FLAG_GUARANTOR'] : null,
                'PRODUCT_TYPE' => $product[0]->ASSETS_TYPE,
                'PRODUCT_CATEGORY' => $product[0]->ASSETS_CATEGORY,
                'PRODUCT_BAND' => $product[0]->BRAND,
                'PRODUCT_SERIES' => $product[0]->SERIES,
                'PRODUCT_SUB_SERIES' => $product[0]->SUB_SERIES,
                'PRODUCT_COLOR' => $product[0]->COLOR,
                'REMARK' => null,
                'PROD_PRICE' => $PROD_PRICE,
                'PROD_VAT' => null,
                'PROD_SUM_PRICE' => null,
                'DOWN_PERCENT' => $data['DOWN_PERCENT'],
                'DOWN_AMT' => null,
                'DOWN_VAT' => null,
                'DOWN_SUM_AMT' => null,
                'HP_AMT' => null,
                'HP_INVEST_AMT' => null,
                'INTEREST_FLAT' => null,
                'INTEREST_EFFECTIVE' => null,
                'INSTALL_NUM' => isset($data['INSTALL_NUM']) ? $data['INSTALL_NUM'] : null,
                'INTEREST_AMT' => null,
                'HP_SUM' => null,
                'INSTALL_NUM_FINAL' => null,
                'INSTALL_AMT' => null,
                'INSTALL_AMT_FINAL' => null,
                'INSTALL_VAT' => null,
                'INSTALL_VAT_FINAL' => null,
                'INSTALL_SUM' => null,
                'INSTALL_SUM_FINAL' => null,
                'CREDIT_LIMIT' => null,
                'HP_VAT_SUM' => null,
                'PAY_DOWN_TYPE' => null,
                'DESCRIPTION' => null,
                'ACS_ID' => isset($data['ACS_ID']) ? $data['ACS_ID'] : null,
                'ACS_DES' => isset($data['ACS_DES']) ? $data['ACS_DES'] : null,
                'ACS_PRICE' => isset($data['ACS_PRICE']) ? $data['ACS_PRICE'] : null,
                'ACS_VAT' => null,
                'ACS_SUM' => null,
                'INSURE_ID' => isset($data['INSURE_ID']) ? $data['INSURE_ID'] : null,
                'INSURE_DES' => isset($data['INSURE_DES']) ? $data['INSURE_DES'] : null,
                'INSURE_SUM' => isset($data['INSURE_SUM']) ? $data['INSURE_SUM'] : null,
                'PROD_TOTAL' => null,
                'PROD_TOTAL_VAT' => null,
                'PROD_TOTAL_AMT' => null,
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

            return response()->json(array(
                'Code' => '9999',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $data['TAX_ID'],
                    'QUATATION_ID' => $ID_QT,
                    'PST_CUST_ID' => $PST_CUST_ID,
                    'ADD_CUST_ID' => $ADD_CUST_ID,
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
                ));
            }

            return response()->json(array(
                'Code' => '0003',
                'status' => 'Error',
                'message' => $e->getMessage()
            ));
        }
    }
}
