<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Arr;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;

use App\Http\Controllers\UFUND\Check_Calculator;
use App\Http\Controllers\UFUND\Error_Exception;
use App\Models\SETUP_COMPANY_BRANCH;
use App\Models\MT_FACULTY;
use App\Models\ASSETS_INFORMATION;
use App\Models\ASSETS_INFORMATION_REF;
use App\Models\MT_INSURE;
use App\Models\SETUP_PRODUCT_CONDITION;
use App\Models\SETUP_PRODUCT_CONDITION_DETAIL;
use App\Models\QUOTATION;
use App\Models\ADDRESS_PROSPECT_CUSTOMER;
use App\Models\PROSPECT_CUSTOMER;
use App\Models\PROSPECT_GUARANTOR;

class ApiNewQuatation extends BaseController
{

    public $Error_Exception;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    public function New_Quatation(Request $request)
    {

        try {

            $data = $request->all();

            $this->validate_input($data);

            $BRANCH_TYPE = $data['BRANCH_TYPE'];
            $BRANCH_ID = $data['BRANCH_ID'];
            $PRODUCT_SERIES = $data['PRODUCT_SERIES'];
            $DOWN_SUM_AMT = (float)$data['DOWN_SUM_AMT'];
            $INSTALL_NUM = $data['INSTALL_NUM'];
            $TAX_ID = $data['TAX_ID'];
            $FIRST_NAME = $data['FIRST_NAME'];
            $LAST_NAME = $data['LAST_NAME'];
            $OCCUPATION_ID = $data['OCCUPATION_ID'];
            $UNIVERSITY_ID = $data['UNIVERSITY_ID'];
            $FACULTY_ID = $data['FACULTY_ID'];
            $ACS_ID = $data['ACS_ID'] ?? null;
            $INSURE_ID = $data['INSURE_ID'] ?? null;
            $PROD_SUM_PRICE = (float)$data['PROD_SUM_PRICE'];
            $Narcotic_ID = $data['Narcotic_ID'];
            $Disease_ID = $data['Disease_ID'];

            // $BIRTHDAY = Carbon::createFromFormat('Y-m-d', $data['BIRTHDAY']);
            $BIRTHDAY = new Carbon($data['BIRTHDAY']);
            $NOW = Carbon::now();
            $AGE = $NOW->diffInYears($BIRTHDAY);

            $request->request->add(
                [
                    'AGE' => $AGE,
                ]
            );

            $ACS_SUM = 0;
            if (isset($data['ACS_ID']) && $data['ACS_ID'] != '') {
                $ACS_SUM = isset($data['ACS_SUM']) ? (float)$data['ACS_SUM'] : 0;
            }
            $INSURE_SUM = 0;
            if (isset($data['INSURE_ID']) && $data['INSURE_ID'] != '') {
                $INSURE_SUM = isset($data['INSURE_SUM']) ? (float)$data['INSURE_SUM'] : 0;
            }


            $this->Check_DupTaxID($TAX_ID);

            // Check University Match Faculty
            $faculty_check = $this->Check_Uni_Fac($data);

            // Check SKU Product
            $product = $this->Check_SKU_product($data);

            // Check ACS / INSURE
            list($DB_ASC, $DB_INSURE) = $this->Check_ACS_INSURE($data);


            // Get BRANCH_AD
            $SETUP_COMPANY_BRANCH = SETUP_COMPANY_BRANCH::where('BRANCH_TYPE', $BRANCH_TYPE)
                ->where('COMP_BRANCH_ID', $BRANCH_ID)
                ->first();
            $BRANCH_AD = isset($SETUP_COMPANY_BRANCH) ? $SETUP_COMPANY_BRANCH->BRANCH_AD : null;


            // -----------------------------------------------------------------------------------------------------------------------------------//

            //---Calcu Prod Price
            // $GET_ACS_SUM = isset($DB_ASC[0]->PRICE) ? $DB_ASC[0]->PRICE : 0;
            $GET_ACS_SUM = $ACS_SUM;
            // $GET_INSURE_SUM = isset($DB_INSURE[0]->INSURE_PRICE) ? $DB_INSURE[0]->INSURE_PRICE : 0;
            $GET_INSURE_SUM = $INSURE_SUM;


            $PRD_PRICE = ASSETS_INFORMATION::select('PRICE', 'MODELNUMBER', 'DESCRIPTION')
                ->where('MODELNUMBER', 'like', '%' . $PRODUCT_SERIES . '%')
                ->get();

            // $PROD_SUM_PRICE = (int)$PRD_PRICE[0]->PRICE;
            $PROD_PRICE_Float = $PROD_SUM_PRICE  * (100 / 107);
            $PROD_PRICE = round((float)$PROD_PRICE_Float, 2);
            // $PROD_VAT = $PROD_SUM_PRICE  * (7 / 107);
            $PROD_VAT = $PROD_SUM_PRICE - $PROD_PRICE;


            $DOWN_AMT_Float = (int)$DOWN_SUM_AMT * (100 / 107);
            $DOWN_AMT = round((float)$DOWN_AMT_Float, 2);
            $DOWN_VAT = $DOWN_SUM_AMT - $DOWN_AMT;


            // $all_vat = number_format((((int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM) * 7) / 107, 2, '.', '');
            // $PROD_TOTAL_AMT = (int)$data['PROD_SUM_PRICE'] + (int)$GET_ACS_SUM + (int)$GET_INSURE_SUM;
            $PROD_TOTAL_AMT = $PROD_SUM_PRICE + $GET_ACS_SUM + $GET_INSURE_SUM;
            $PROD_TOTAL_Float = $PROD_TOTAL_AMT  * (100 / 107);
            $PROD_TOTAL = round((float)$PROD_TOTAL_Float, 2);
            $PROD_TOTAL_VAT = $PROD_TOTAL_AMT - $PROD_TOTAL;


            $ACS_VAT_CAL = round(($GET_ACS_SUM * 0.07), 2);
            $ACS_VAT = $ACS_VAT_CAL == 0 ? 0 : $ACS_VAT_CAL;

            $ACS_PRICE_CAL = $GET_ACS_SUM - $ACS_VAT;
            $ACS_PRICE = $ACS_PRICE_CAL == 0 ? 0 : $ACS_PRICE_CAL;

            // Cal Down percent
            $DOWN_PERCENT = ($DOWN_SUM_AMT / $PROD_TOTAL_AMT);
            // dd($PROD_TOTAL_AMT);

            // Check Down Guarantor
            $check_Down = $this->Check_Guarantor($data, $product);

            // หา INTEREST_FLAT
            $BRAND = $product[0]->BRAND;
            $HP_PRODUCT_ID = SETUP_PRODUCT_CONDITION::select('*')
                ->where('PRODUCT_BAND', $BRAND)
                ->get();

            $Get_INTEREST_FLAT = SETUP_PRODUCT_CONDITION_DETAIL::select('*')
                // ->where('HP_PRODUCT_ID', $HP_PRODUCT_ID[0]->HP_PRODUCT_ID)
                ->limit(1)
                ->first();


            $INTEREST_FLAT = (float)$Get_INTEREST_FLAT->INTEREST;

            $HP_AMT = round(($PROD_TOTAL_AMT - (float)$DOWN_SUM_AMT) / 1.07, 2);

            $INTEREST_AMT = round(($HP_AMT * $INTEREST_FLAT) * (int)$INSTALL_NUM, 2);

            $HP_SUM = round($HP_AMT + $INTEREST_AMT, 2);

            $INSTALL_AMT =  round($HP_SUM / $INSTALL_NUM, 2);

            $INSTALL_VAT = round(($HP_SUM / (int)$INSTALL_NUM) * 7 / 100, 2);

            $INSTALL_SUM = $INSTALL_AMT + $INSTALL_VAT;

            $HP_VAT_SUM = round(($HP_AMT * 1.07) + ($INTEREST_AMT * 1.07), 2);


            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
            $date_end = Carbon::now(new DateTimeZone('Asia/Bangkok'))->addDays(15)->format('Y-m-d H:i:s');

            // INTEREST_EFFECTIVE
            $Check_Calculator = new Check_Calculator;
            $nper = $INSTALL_NUM;
            $pmt = $INSTALL_AMT;
            $pv = - ($HP_AMT);
            // dd($HP_AMT);
            $fv = 0;
            $type = 0;
            $guess = 0.1;
            $EFFECTIVE = strval(round($Check_Calculator->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12, 8));

            // dd($EFFECTIVE);
            // var_dump(($this->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12) * 100);

            // dd($product[0]->SERIES);

            $DEFAULT_DOWN_PERCENT = number_format((float)$check_Down[0]->{'@DownAMT_PERCENT_OUTPUT'}, 2, '.', '');


            // I-Care amount
            $SP_parameters_Icare = [
                'narcotic_id' =>  $Narcotic_ID,
                'disease_id' => $Disease_ID,
                'install_num' => $INSTALL_NUM,
                'age' => $AGE,
            ];

            $sql_Icare = "SET NOCOUNT ON; exec SP_CHECK_ICARE 
                @narcotic_id=:narcotic_id,  
                @disease_id=:disease_id, 
                @install_num=:install_num,
                @age=:age";

            $SP_ICARE = DB::select($sql_Icare, $SP_parameters_Icare);


            // Cal Price
            $SP_parameters = [
                'prod_price' => $PROD_SUM_PRICE,
                'install' => $INSTALL_NUM,
                'down_per' => $DOWN_PERCENT,
                'dowm_sum_amt' => $DOWN_SUM_AMT,
                'icare_price' => isset($SP_ICARE[0]->{'Price value'}) ? $SP_ICARE[0]->{'Price value'} : 0,
                'warranty_price' => $INSURE_SUM,
                'acs_sum' => $ACS_SUM,
                'fac_id' => $FACULTY_ID,
                'uni_id' => $UNIVERSITY_ID,
                'cate_id' => $product[0]->ASSETS_CATEGORY,
                'series_id' => $product[0]->SERIES,
                // output
                'DownMAX' => 0,
                'Guarantor' => 0,
                'CheckDefault' => 0,
                'DownAMT_OUTPUT' => 0,
                'DownAMT_PERCENT_OUTPUT' => $DEFAULT_DOWN_PERCENT,
                //Default
                'vat_per' => 0.07,
                'interest_per' => 0.0275,
            ];

            // Prepare the SQL statement
            $sql_cal_install = "SET NOCOUNT ON; exec SP_CALCULATE_INSTALL_AMOUNT 
                @prod_price=:prod_price,  
                @install=:install, 
                @down_per=:down_per,
                @dowm_sum_amt=:dowm_sum_amt,
                @icare_price=:icare_price,
                @warranty_price=:warranty_price,
                @acs_sum=:acs_sum,
                @fac_id=:fac_id,
                @uni_id=:uni_id,
                @cate_id=:cate_id,
                @series_id=:series_id,
                @DownMAX=:DownMAX,
                @Guarantor=:Guarantor,
                @CheckDefault=:CheckDefault,
                @DownAMT_OUTPUT=:DownAMT_OUTPUT,
                @DownAMT_PERCENT_OUTPUT=:DownAMT_PERCENT_OUTPUT,
                @vat_per=:vat_per,
                @interest_per=:interest_per";

            // Execute the stored procedure
            $SP_CALCULATE = DB::select($sql_cal_install, $SP_parameters);


            $QUOTATION = new QUOTATION([
                'QT_DATE' => $date_now,
                'DATE_END' => $date_end,
                'STATUS_ID' => 27,
                'APPROVE_CODE' => null,
                'BRANCH_TYPE' => $BRANCH_TYPE,
                'BRANCH_ID' => $BRANCH_ID,
                'BRANCH_AD' => $BRANCH_AD,
                'TAX_ID' => $TAX_ID,
                'CUSTOMER_NAME' => $FIRST_NAME . ' ' . $LAST_NAME,
                'OCCUPATION_ID' => $OCCUPATION_ID,
                'UNIVERSITY_ID' => $UNIVERSITY_ID,
                'CAMPUS_ID' => $faculty_check[0]->MT_CAMPUS_ID,
                'FACULTY_ID' => $FACULTY_ID,
                'FLAG_GUARANTOR' => $check_Down[0]->Guarantor == 1 ? 1 : null,
                'PRODUCT_TYPE' => $product[0]->ASSETS_TYPE,
                'PRODUCT_CATEGORY' => $product[0]->ASSETS_CATEGORY,
                'PRODUCT_BAND' => $product[0]->BRAND,
                'PRODUCT_SERIES' => $product[0]->SERIES,
                'PRODUCT_SUB_SERIES' => $product[0]->SUB_SERIES,
                'PRODUCT_COLOR' => $product[0]->COLOR,
                'REMARK' => null,
                // 'PROD_PRICE' => $PROD_PRICE,
                'PROD_PRICE' => round($SP_CALCULATE[0]->prod_amt, 2),
                // 'PROD_VAT' => $PROD_VAT,
                'PROD_VAT' =>  round($SP_CALCULATE[0]->prod_vat, 2),
                'PROD_SUM_PRICE' => $PROD_SUM_PRICE,
                'DOWN_PERCENT' => $DOWN_PERCENT,
                // 'DOWN_AMT' =>  $DOWN_AMT,
                'DOWN_AMT' =>  $SP_CALCULATE[0]->down_amt,
                // 'DOWN_VAT' => $DOWN_VAT,
                'DOWN_VAT' =>  $SP_CALCULATE[0]->down_vat,
                'DOWN_SUM_AMT' => $DOWN_SUM_AMT,
                'HP_AMT' => $SP_CALCULATE[0]->hp_amt,
                'HP_INVEST_AMT' => $SP_CALCULATE[0]->hp_amt,
                'INTEREST_FLAT' => $INTEREST_FLAT,
                'INTEREST_EFFECTIVE' => $EFFECTIVE,
                'INSTALL_NUM' => $INSTALL_NUM,
                // 'INTEREST_TOTAL' => null,
                // 'INTEREST_VAT' => null,
                // 'INTEREST_AMT' => $INTEREST_AMT,
                'INTEREST_AMT' =>  $SP_CALCULATE[0]->INTEREST_AMT,
                // 'HP_SUM' => $HP_SUM,
                'HP_SUM' =>  $SP_CALCULATE[0]->HP_SUM,
                // 'INSTALL_NUM_FINAL' => (int)$INSTALL_NUM - 1,
                'INSTALL_NUM_FINAL' =>  (int)$SP_CALCULATE[0]->INSTALL_NUM_FINAL,
                // 'INSTALL_AMT' => $INSTALL_AMT,
                'INSTALL_AMT' =>  $SP_CALCULATE[0]->INSTALL_AMT,
                'INSTALL_AMT_FINAL' => $SP_CALCULATE[0]->INSTALL_AMT,
                // 'INSTALL_VAT' => $INSTALL_VAT,
                'INSTALL_VAT' =>  $SP_CALCULATE[0]->INSTALL_VAT,
                'INSTALL_VAT_FINAL' => $SP_CALCULATE[0]->INSTALL_VAT,
                // 'INSTALL_SUM' => $INSTALL_SUM,
                'INSTALL_SUM' =>  $SP_CALCULATE[0]->INSTALL_SUM,
                'INSTALL_SUM_FINAL' => $SP_CALCULATE[0]->INSTALL_SUM,
                // 'CREDIT_LIMIT' => $PROD_TOTAL_AMT,
                'CREDIT_LIMIT' => $SP_CALCULATE[0]->CREDIT_LIMIT,
                // 'HP_VAT_SUM' => $HP_VAT_SUM,
                'HP_VAT_SUM' => $SP_CALCULATE[0]->HP_VAT_SUM,
                'PAY_DOWN_TYPE' => 1,
                'DESCRIPTION' => null,
                'ACS_ID' => isset($ACS_ID) ? $ACS_ID : null,
                'ACS_DES' => isset($DB_ASC[0]->DESCRIPTION) ? $DB_ASC[0]->DESCRIPTION : null,
                // 'ACS_PRICE' => $ACS_PRICE,
                'ACS_PRICE' => round($SP_CALCULATE[0]->ACS_PRICE, 2),
                // 'ACS_VAT' => $ACS_VAT,
                'ACS_VAT' => round($SP_CALCULATE[0]->ACS_VAT, 2),
                'ACS_SUM' =>  $ACS_SUM,
                'INSURE_ID' => isset($INSURE_ID) ? $INSURE_ID : null,
                'INSURE_DES' => isset($DB_INSURE[0]->INSURE_PRODUCT_NAME) ? $DB_INSURE[0]->INSURE_PRODUCT_NAME : null,
                'INSURE_SUM' => $INSURE_SUM,
                // 'INSURE_VAT' => null,
                // 'INSURE_TOTAL' => null,
                // 'PROD_TOTAL' => $PROD_TOTAL,
                'PROD_TOTAL' => $SP_CALCULATE[0]->prd_total,
                // 'PROD_TOTAL_VAT' => $PROD_TOTAL_VAT,
                'PROD_TOTAL_VAT' => $SP_CALCULATE[0]->prd_total_vat,
                // 'PROD_TOTAL_AMT' => $PROD_TOTAL_AMT,
                'PROD_TOTAL_AMT' => $SP_CALCULATE[0]->prd_total_amt,
                // 'Tradein_AMT' => null,
                // 'ADDR_PROS_KYC' => null,
                // 'DUEDATE_KYC' => null,
                // 'PERIOD_KYC' => null,
                'Addr_KYC_ID' => null,
                'Delivery_ID' => null,
                'CREATE_DATE' => $date_now,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => 'API',
                'DEFAULT_DOWN_PERCENT' => $DEFAULT_DOWN_PERCENT,
                'Icare_Des' => isset($SP_ICARE[0]->{'Price value'}) ? 'Icare Insurance' : $SP_ICARE[0]->{''},
                'Icare_Percent' => null,
                'Icare_Price' => isset($SP_ICARE[0]->{'Price value'}) ? $SP_ICARE[0]->{'Price value'} : null,
                // 'DOWN_SUM_VAT' => null,
                // 'DOWN_SUM_TOTAL' => null,
                // 'DOWN_PAY_AMT' => null,
                // 'AMT_PERCENT' => null,
                // 'TYPE_LOAN_HP' => null,
                // 'FEE_TOTAL_AMT' => null,
                // 'FEE_TOTAL' => null,
                // 'FEE_VAT' => null,
                // 'Balloon_Type' => null,
                // 'TRADE_IN_TYPE' => null,
                // 'INSTALL_NUM_BALLOON' => null,
                // 'TRADE_IN_INSTALL' => null,
                // 'PACKAGE_ID' => null,
                // 'PACKAGE_AMT' => null,
                // 'PACKAGE_VAT' => null,
                // 'PACKAGE_SUM_AMT' => null,
                // 'DISCOUNT_AMT' => null,
                // 'DISCOUNT_VAT' => null,
                // 'DISCOUNT_SUM_AMT' => null,
                // 'TRADE_IN_DISCOUNT_AMT' => null,
                // 'TRADE_IN_DISCOUNT_VAT' => null,
                // 'TRADE_IN_DISCOUNT_SUM_AMT' => null,
                'UTM_CODE' => 'Mobile',
            ]);

            $QUOTATION->save();

            $ID_QT = $QUOTATION->QUOTATION_ID;

            $QUOTATION->APPROVE_CODE = $ID_QT + 100000;
            $QUOTATION->save();

            $PROSPECT_CUSTOMER = new PROSPECT_CUSTOMER([
                'QUOTATION_ID' => $ID_QT,
                'TAX_ID' =>  $TAX_ID,
            ]);
            $PROSPECT_CUSTOMER->save();

            $PST_CUST_ID = $PROSPECT_CUSTOMER->PST_CUST_ID;


            $ADDRESS_PROSPECT_CUSTOMER = new ADDRESS_PROSPECT_CUSTOMER([
                'QUOTATION_ID' => $ID_QT,
                'PST_CUST_ID' =>  $PST_CUST_ID,
            ]);
            $ADDRESS_PROSPECT_CUSTOMER->save();

            $ADD_CUST_ID = $ADDRESS_PROSPECT_CUSTOMER->ADD_CUST_ID;

            if ($check_Down[0]->Guarantor == 1) {
                $PROSPECT_GUARANTOR = new PROSPECT_GUARANTOR([
                    'QUOTATION_ID' => $ID_QT,
                    'PST_CUST_ID' =>  $PST_CUST_ID,
                ]);
                $PROSPECT_GUARANTOR->save();
            }


            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $TAX_ID,
                    'QUOTATION_ID' => $ID_QT,
                    'PST_CUST_ID' => $PST_CUST_ID,
                    'ADD_CUST_ID' => $ADD_CUST_ID,
                    'RequestGUARANTOR' => $check_Down[0]->Guarantor == '1' ? '1' : '0',
                    'PST_GUAR_ID' => isset($PROSPECT_GUARANTOR->PST_GUAR_ID) ? $PROSPECT_GUARANTOR->PST_GUAR_ID : '',

                    // 'DB_QUOTATION' => $QUOTATION,
                    // 'DB_PROSPECT_CUSTOMER' => $PROSPECT_CUSTOMER,
                    // 'DB_ADDRESS_PROSPECT_CUSTOMER' => $ADDRESS_PROSPECT_CUSTOMER,
                    // 'DB_PROSPECT_GUARANTOR' => $PROSPECT_GUARANTOR ?? null,
                ]
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }

    function validate_input($data)
    {
        $validationRules = [
            'BRANCH_TYPE' => 'required|integer',
            'BRANCH_ID' => 'required|integer',
            'TAX_ID'  => 'required|numeric|digits:13',
            'FIRST_NAME' => 'required|string',
            'LAST_NAME' => 'required|string',
            'OCCUPATION_ID' => 'required|integer',
            'UNIVERSITY_ID' => 'required|integer',
            'FACULTY_ID' => 'required|integer',
            'PRODUCT_SERIES' => 'required|integer',
            'DOWN_SUM_AMT' => 'required|numeric',
            'INSTALL_NUM' => 'required|integer',
            'PROD_SUM_PRICE' => 'required|numeric',
            'Narcotic_ID' => 'required|numeric',
            'Disease_ID' => 'required|numeric',
        ];

        $messages = []; //custom message error. (this line for use defualt)

        $attributeNames = [
            'BRANCH_TYPE' => 'BRANCH_TYPE',
            'BRANCH_ID' => 'BRANCH_ID',
            'TAX_ID' => 'TAX_ID',
            'FIRST_NAME' => 'FIRST_NAME',
            'LAST_NAME' => 'LAST_NAME',
            'OCCUPATION_ID' => 'OCCUPATION_ID',
            'UNIVERSITY_ID' => 'UNIVERSITY_ID',
            'FACULTY_ID' => 'FACULTY_ID',
            'PRODUCT_SERIES' => 'PRODUCT_SERIES',
            'DOWN_SUM_AMT' => 'DOWN_SUM_AMT',
            'INSTALL_NUM' => 'INSTALL_NUM',
            'PROD_SUM_PRICE' => 'PROD_SUM_PRICE',
            'Narcotic_ID' => 'Narcotic_ID',
            'Disease_ID' => 'Disease_ID',
        ];

        $validator = Validator::make($data, $validationRules, $messages, $attributeNames);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first(), 1000);
        }
    }


    function Check_DupTaxID($TAX_ID)
    {
        $check_TAX = DB::select("exec SP_CheckDupAppContractByTAXID  @tax_id = '" . $TAX_ID . "' ");
        if (count($check_TAX) > 0) {
            throw new Exception("[TAX_ID] is already exists", 2000);
        }
    }

    function Check_Uni_Fac($data)
    {
        // Check University Match Faculty
        $MT_FACULTY = MT_FACULTY::where('MT_FACULTY_ID', $data['FACULTY_ID'])
            ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
            ->get();

        if (count($MT_FACULTY) == 0) {
            throw new Exception("[FACULTY_ID] and [UNIVERSITY_ID] is not match", 2000);
        }

        return $MT_FACULTY;
    }


    function Check_SKU_product($data)
    {
        $product = ASSETS_INFORMATION::WithSeriesLike($data['PRODUCT_SERIES'])->get();

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

        $ASSETS_INFORMATION_REF = ASSETS_INFORMATION_REF::findWithAssetsInformation($data['ACS_ID']);

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

        $MT_INSURE = MT_INSURE::where('INSURE_ID', '=', $data['INSURE_ID'])->get();

        return array($ASSETS_INFORMATION_REF, $MT_INSURE);
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
