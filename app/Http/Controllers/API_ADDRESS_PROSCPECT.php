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

class API_ADDRESS_PROSCPECT extends BaseController
{
    public function __construct()
    {
        // A1.ทะเบียนบ้าน
        // A2.ที่อยู่ปัจจุบัน
        // A3.ที่อยู่จัดส่งสินค้า หรือ จัดส่งเอกสาร
    }

    public function NEW_ADDRESS_PROSCPECT(Request $request)
    {
        try {

            $data = $request->all();

            $validate = [
                "QUOTATION_ID" => [
                    'message' => 'Request Parameter [QUOTATION_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [QUOTATION_ID]',
                    //     'EN' => 'Request Parameter [QUOTATION_ID]'
                    // ],
                    'numeric' => true,
                ],
                "PST_CUST_ID" => [
                    'message' => 'Request Parameter [PST_CUST_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [PST_CUST_ID]',
                    //     'EN' => 'Request Parameter [PST_CUST_ID]'
                    // ],
                    'numeric' => true,
                ],
                "A1_NO" => [
                    'message' => 'Request Parameter [A1_NO]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขที่อยู่',
                    //     'EN' => 'Please identify address number'
                    // ],
                    'numeric' => false,
                ],
                "A1_PROVINCE" => [
                    'message' => 'Request Parameter [A1_PROVINCE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลจังหวัด',
                    //     'EN' => 'Please identify province'
                    // ],
                    'numeric' => true,
                ],
                "A1_DISTRICT" => [
                    'message' => 'Request Parameter [A1_DISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลอำเภอ',
                    //     'EN' => 'Please identify district'
                    // ],
                    'numeric' => true,
                ],
                "A1_SUBDISTRICT" => [
                    'message' => 'Request Parameter [A1_SUBDISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลตำบล',
                    //     'EN' => 'Please identify subdistrict'
                    // ],
                    'numeric' => true,
                ],
                "A1_POSTALCODE" => [
                    'message' => 'Request Parameter [A1_POSTALCODE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลรหัสไปรษณี',
                    //     'EN' => 'Please identify postcode'
                    // ],
                    'numeric' => true,
                ],
                "A1_OWNER_TYPE" => [
                    'message' => 'Request Parameter [A1_OWNER_TYPE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลสถานะการพักอาศัย',
                    //     'EN' => 'Please identify owner type'
                    // ],
                    'numeric' => true,
                ],
                "A2_NO" => [
                    'message' => 'Request Parameter [A2_NO]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขที่อยู่',
                    //     'EN' => 'Please identify address number'
                    // ],
                    'numeric' => false,
                ],
                "A2_PROVINCE" => [
                    'message' => 'Request Parameter [A2_PROVINCE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลจังหวัด',
                    //     'EN' => 'Please identify provice'
                    // ],
                    'numeric' => true,
                ],
                "A2_DISTRICT" => [
                    'message' => 'Request Parameter [A2_DISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลอำเภอ',
                    //     'EN' => 'Please identify district'
                    // ],
                    'numeric' => true,
                ],
                "A2_SUBDISTRICT" => [
                    'message' => 'Request Parameter [A2_SUBDISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลตำบล',
                    //     'EN' => 'Please identify subdistrict'
                    // ],
                    'numeric' => true,
                ],
                "A2_POSTALCODE" => [
                    'message' => 'Request Parameter [A2_POSTALCODE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลรหัสไปรษณี',
                    //     'EN' => 'Please identify postcode'
                    // ],
                    'numeric' => true,
                ],
                "A2_OWNER_TYPE" => [
                    'message' => 'Request Parameter [A2_OWNER_TYPE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลสถานะการพักอาศัย',
                    //     'EN' => 'Please identify owner type'
                    // ],
                    'numeric' => true,
                ],
                "A3_NO" => [
                    'message' => 'Request Parameter [A3_NO]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขที่อยู่',
                    //     'EN' => 'Please identify address number'
                    // ],
                    'numeric' => false,
                ],
                "A3_PROVINCE" => [
                    'message' => 'Request Parameter [A3_PROVINCE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลจังหวัด',
                    //     'EN' => 'Please identify provice'
                    // ],
                    'numeric' => true,
                ],
                "A3_DISTRICT" => [
                    'message' => 'Request Parameter [A3_DISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลอำเภอ',
                    //     'EN' => 'Please identify district'
                    // ],
                    'numeric' => true,
                ],
                "A3_SUBDISTRICT" => [
                    'message' => 'Request Parameter [A3_SUBDISTRICT]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลตำบล',
                    //     'EN' => 'Please identify subdistrict'
                    // ],
                    'numeric' => true,
                ],
                "A3_POSTALCODE" => [
                    'message' => 'Request Parameter [A3_POSTALCODE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลรหัสไปรษณี',
                    //     'EN' => 'Please identify postcode'
                    // ],
                    'numeric' => true,
                ],
                "A3_OWNER_TYPE" => [
                    'message' => 'Request Parameter [A3_OWNER_TYPE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุข้อมูลสถานะการพักอาศัย',
                    //     'EN' => 'Please identify owner type'
                    // ],
                    'numeric' => true,
                ],
            ];

            foreach ($validate as $key => $value) {
                if (!isset($data[$key])) {
                    // throw new Exception(json_encode($value['message']));
                    throw new Exception($value['message'], 1000);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . '] ', 1000);
                        // $mes_error = new stdClass;
                        // foreach($value['message'] as $key => $value){
                        //     $txt = ($key == "TH" ? $value."ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    }
                }
            }


            // Get ADDRESS_PROSPECT_CUSTOMER
            $GET_ADDRESS_PROSPECT_CUSTOMER = DB::table('dbo.ADDRESS_PROSPECT_CUSTOMER')
                ->select('*')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->orderBy('QUOTATION_ID', 'DESC')
                ->get();

            if (count($GET_ADDRESS_PROSPECT_CUSTOMER) == 0) {
                throw new Exception('Not found Data. Check Parameter [\'PST_CUST_ID\'] , [\'QUOTATION_ID\']', 2000);
                // $mes_error = (object)[
                //     'TH' => 'ไม่พบข้อมูลของท่าน',
                //     'EN' => 'Not found your Information'
                // ];
                // throw new Exception(json_encode($mes_error));
            }



            $checkAddress = [
                "A1" => [
                    "PROVINCE" => "A1_PROVINCE", "DISTRICT" => "A1_DISTRICT", "SUBDISTRICT" => "A1_SUBDISTRICT", "POSTALCODE" => "A1_POSTALCODE"
                ],
                "A2" => [
                    "PROVINCE" => "A2_PROVINCE", "DISTRICT" => "A2_DISTRICT", "SUBDISTRICT" => "A2_SUBDISTRICT", "POSTALCODE" => "A2_POSTALCODE"
                ],
                "A3" => [
                    "PROVINCE" => "A3_PROVINCE", "DISTRICT" => "A3_DISTRICT", "SUBDISTRICT" =>  "A3_SUBDISTRICT", "POSTALCODE" => "A3_POSTALCODE"
                ],
            ];

            
            foreach ($checkAddress as $key) {
                // echo $key['PROVINCE'];
                $GetAddress = DB::table('dbo.MT_POST_CODE')
                    ->select('MT_POST_CODE.*')
                    ->leftJoin('MT_SUB_DISTRICT', 'MT_POST_CODE.SUB_DISTRICT_ID', '=', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID')
                    ->leftJoin('MT_DISTRICT', 'MT_SUB_DISTRICT.DISTRICT_ID', '=', 'MT_DISTRICT.DISTRICT_ID')
                    ->leftJoin('MT_PROVINCE', 'MT_DISTRICT.PROVINCE_ID', '=', 'MT_PROVINCE.PROVINCE_ID')
                    ->where('MT_PROVINCE.PROVINCE_ID', $data[$key['PROVINCE']])
                    ->where('MT_DISTRICT.DISTRICT_ID', $data[$key['DISTRICT']])
                    ->where('MT_SUB_DISTRICT.SUB_DISTRICT_ID', $data[$key['SUBDISTRICT']])
                    ->where('MT_POST_CODE.POST_CODE_ID', $data[$key['POSTALCODE']])
                    ->count();

                if ($GetAddress == 0) {
                    throw new Exception('Address is not match. Check Parameter [\'' . $key['PROVINCE'] . '\'], [\'' . $key['DISTRICT'] . '\'], [\'' . $key['SUBDISTRICT'] . '\'], [\'' . $key['POSTALCODE'] . '\']', 2000);
                    // $mes_error = (object)[
                    //     'TH' => 'ข้อมูลที่อยู่ไม่ถูกต้อง',
                    //     'EN' => 'Address invalid'
                    // ];
                    // throw new Exception(json_encode($mes_error));
                }
            }


            $check_owner_type = [
                'A1_OWNER_TYPE' , 'A2_OWNER_TYPE' , 'A3_OWNER_TYPE' , 
            ];

            foreach ($check_owner_type as $key) {
                $Get_owner_type = DB::table('dbo.MT_POST_CODE')
                    ->select('MT_POST_CODE.*')
                    ->count();
            }

            DB::table('dbo.ADDRESS_PROSPECT_CUSTOMER')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->update([
                    'QUOTATION_ID' => $data['QUOTATION_ID'],
                    'PST_CUST_ID' => $data['PST_CUST_ID'],
                    'A1_MASTER' => 1,
                    'A1_NO' => $data['A1_NO'],
                    'A1_MOI' => isset($data['A1_MOI']) ? $data['A1_MOI'] : null,
                    'A1_VILLAGE' => isset($data['A1_VILLAGE']) ? $data['A1_VILLAGE'] : null,
                    'A1_BUILDING' => isset($data['A1_BUILDING']) ? $data['A1_BUILDING'] : null,
                    'A1_FLOOR' => isset($data['A1_FLOOR']) ? $data['A1_FLOOR'] : null,
                    'A1_ROOM_NO' => isset($data['A1_ROOM_NO']) ? $data['A1_ROOM_NO'] : null,
                    'A1_SOI' => isset($data['A1_SOI']) ? $data['A1_SOI'] : null,
                    'A1_ROAD' => isset($data['A1_ROAD']) ? $data['A1_ROAD'] : null,
                    'A1_PROVINCE' => $data['A1_PROVINCE'],
                    'A1_DISTRICT' => $data['A1_DISTRICT'],
                    'A1_SUBDISTRICT' => $data['A1_SUBDISTRICT'],
                    'A1_POSTALCODE' => $data['A1_POSTALCODE'],
                    'A1_OWNER_TYPE' => $data['A1_OWNER_TYPE'],
                    'A1_LIVEING_TIME' => isset($data['A1_LIVEING_TIME']) ? $data['A1_LIVEING_TIME'] : null,
                    'A1_PHONE' => isset($data['A1_PHONE']) ? $data['A1_PHONE'] : null,
                    'A2_MASTER' => 2,
                    'A2_NO' => $data['A2_NO'],
                    'A2_MOI' => isset($data['A2_MOI']) ? $data['A2_MOI'] : null,
                    'A2_VILLAGE' => isset($data['A2_VILLAGE']) ? $data['A2_VILLAGE'] : null,
                    'A2_BUILDING' => isset($data['A2_BUILDING']) ? $data['A2_BUILDING'] : null,
                    'A2_FLOOR' => isset($data['A2_FLOOR']) ? $data['A2_FLOOR'] : null,
                    'A2_ROOM_NO' => isset($data['A2_ROOM_NO']) ? $data['A2_ROOM_NO'] : null,
                    'A2_SOI' => isset($data['A2_SOI']) ? $data['A2_SOI'] : null,
                    'A2_ROAD' => isset($data['A2_ROAD']) ? $data['A2_ROAD'] : null,
                    'A2_PROVINCE' => $data['A2_PROVINCE'],
                    'A2_DISTRICT' => $data['A2_DISTRICT'],
                    'A2_SUBDISTRICT' => $data['A2_SUBDISTRICT'],
                    'A2_POSTALCODE' => $data['A2_POSTALCODE'],
                    'A2_OWNER_TYPE' => $data['A2_OWNER_TYPE'],
                    'A2_LIVEING_TIME' => isset($data['A2_LIVEING_TIME']) ? $data['A2_LIVEING_TIME'] : null,
                    'A2_PHONE' => isset($data['A2_PHONE']) ? $data['A2_PHONE'] : null,
                    'A3_MASTER' => 3,
                    'A3_NO' => $data['A3_NO'],
                    'A3_MOI' => isset($data['A3_MOI']) ? $data['A3_MOI'] : null,
                    'A3_VILLAGE' => isset($data['A3_VILLAGE']) ? $data['A3_VILLAGE'] : null,
                    'A3_BUILDING' => isset($data['A3_BUILDING']) ? $data['A3_BUILDING'] : null,
                    'A3_FLOOR' => isset($data['A3_FLOOR']) ? $data['A3_FLOOR'] : null,
                    'A3_ROOM_NO' => isset($data['A3_ROOM_NO']) ? $data['A3_ROOM_NO'] : null,
                    'A3_SOI' => isset($data['A3_SOI']) ? $data['A3_SOI'] : null,
                    'A3_ROAD' => isset($data['A3_ROAD']) ? $data['A3_ROAD'] : null,
                    'A3_PROVINCE' => $data['A3_PROVINCE'],
                    'A3_DISTRICT' => $data['A3_DISTRICT'],
                    'A3_SUBDISTRICT' => $data['A3_SUBDISTRICT'],
                    'A3_POSTALCODE' => $data['A3_POSTALCODE'],
                    'A3_OWNER_TYPE' => $data['A3_OWNER_TYPE'],
                    'A3_LIVEING_TIME' => isset($data['A3_LIVEING_TIME']) ? $data['A3_LIVEING_TIME'] : null,
                    'A3_PHONE' => isset($data['A3_PHONE']) ? $data['A3_PHONE'] : null,
                ]);


                return response()->json(array(
                    'Code' => '0000',
                    'status' => 'Success',
                    'data' => [
                        'QUOTATION_ID' => $data['QUOTATION_ID'],
                        'PST_CUST_ID' => $data['PST_CUST_ID']
                    ]
                ));

        } catch (Exception $e) {
            // dd($e->getMessage());
            // $getPrevious = $e->getPrevious();

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

            if ($e->getPrevious() != null) {
                return response()->json(array(
                    'Code' => '9000',
                    'status' =>  'System Error',
                    'message' => $e->getPrevious()->getMessage(),
                    // 'message' => 'Data invalid. Please check data'
                    // 'message' => [
                    //     'TH' => 'ข้อมูลไม่ถูกต้อง โปรดลองอีกครั้ง',
                    //     'EN' => 'Data invalid. Please try again'
                    // ]

                ));
            }

            return response()->json(array(
                'Code' => (string)$e->getCode() ?: '1000',
                'status' => $MsgError[(string)$e->getCode()]['status'] ?: 'Invalid Data' ,
                'message' => $e->getMessage()
                // 'message' => 'System Error. Please try again'
            ));
        }
    }
}
