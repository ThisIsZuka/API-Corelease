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
use App\Http\Controllers\UFUND\Error_Exception;
use App\Models\ADDRESS_PROSPECT_CUSTOMER;
use App\Models\MT_POST_CODE;

class ApiNewAddressProspectCus extends BaseController
{

    private $Error_Exception;

    public function __construct()
    {
        // A1.ทะเบียนบ้าน
        // A2.ที่อยู่ปัจจุบัน
        // A3.ที่อยู่จัดส่งสินค้า หรือ จัดส่งเอกสาร

        $this->Error_Exception = new Error_Exception;
    }

    public function NEW_ADDRESS_PROSPECT(Request $request)
    {
        try {

            $data = $request->all();

            $validationRules = [
                'QUOTATION_ID' => 'required|integer',
                'PST_CUST_ID' => 'required|integer',
                'A1_NO' => 'required|string',
                'A1_PROVINCE' => 'required|integer',
                'A1_DISTRICT' => 'required|integer',
                'A1_SUBDISTRICT' => 'required|integer',
                'A1_POSTALCODE' => 'required|integer',
                'A1_OWNER_TYPE' => 'required|integer',
                'A2_NO' => 'required|string',
                'A2_PROVINCE' => 'required|integer',
                'A2_DISTRICT' => 'required|integer',
                'A2_SUBDISTRICT' => 'required|integer',
                'A2_POSTALCODE' => 'required|integer',
                'A2_OWNER_TYPE' => 'required|integer',
                'A3_NO' => 'required|string',
                'A3_PROVINCE' => 'required|integer',
                'A3_DISTRICT' => 'required|integer',
                'A3_SUBDISTRICT' => 'required|integer',
                'A3_POSTALCODE' => 'required|integer',
                'A3_OWNER_TYPE' => 'required|integer',
            ];

            $messages = []; //custom message error. (this line for use defualt)

            $attributeNames = [
                'QUOTATION_ID' => 'QUOTATION_ID',
                'PST_CUST_ID' => 'PST_CUST_ID',
                'A1_NO' => 'A1_NO',
                'A1_PROVINCE' => 'A1_PROVINCE',
                'A1_DISTRICT' => 'A1_DISTRICT',
                'A1_SUBDISTRICT' => 'A1_SUBDISTRICT',
                'A1_POSTALCODE' => 'A1_POSTALCODE',
                'A1_OWNER_TYPE' => 'A1_OWNER_TYPE',
                'A2_NO' => 'A2_NO',
                'A2_PROVINCE' => 'A2_PROVINCE',
                'A2_DISTRICT' => 'A2_DISTRICT',
                'A2_SUBDISTRICT' => 'A2_SUBDISTRICT',
                'A2_POSTALCODE' => 'A2_POSTALCODE',
                'A2_OWNER_TYPE' => 'A2_OWNER_TYPE',
                'A3_NO' => 'A3_NO',
                'A3_PROVINCE' => 'A3_PROVINCE',
                'A3_DISTRICT' => 'A3_DISTRICT',
                'A3_SUBDISTRICT' => 'A3_SUBDISTRICT',
                'A3_POSTALCODE' => 'A3_POSTALCODE',
                'A3_OWNER_TYPE' => 'A3_OWNER_TYPE',
            ];

            $validator = Validator::make($data, $validationRules, $messages, $attributeNames);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 1000);
            }


            // Get ADDRESS_PROSPECT_CUSTOMER
            $ADDRESS_PROSPECT_CUSTOMER = ADDRESS_PROSPECT_CUSTOMER::where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->orderBy('QUOTATION_ID', 'DESC')
                ->get();

            if (count($ADDRESS_PROSPECT_CUSTOMER) == 0) {
                throw new Exception('Not found Data. Check Parameter [\'PST_CUST_ID\'] , [\'QUOTATION_ID\']', 2000);
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
                $MT_POST_CODE = MT_POST_CODE::CheckMTPostCode($data[$key['PROVINCE']], $data[$key['DISTRICT']], $data[$key['SUBDISTRICT']], $data[$key['POSTALCODE']]);

                if (count($MT_POST_CODE) == 0) {
                    throw new Exception('Address is not match. Check Parameter [\'' . $key['PROVINCE'] . '\'], [\'' . $key['DISTRICT'] . '\'], [\'' . $key['SUBDISTRICT'] . '\'], [\'' . $key['POSTALCODE'] . '\']', 2000);
                }
            }


            $check_owner_type = [
                'A1_OWNER_TYPE', 'A2_OWNER_TYPE', 'A3_OWNER_TYPE',
            ];

            foreach ($check_owner_type as $key) {
                $Get_owner_type = DB::table('dbo.MT_POST_CODE')
                    ->select('MT_POST_CODE.*')
                    ->count();
            }

            ADDRESS_PROSPECT_CUSTOMER::where('PST_CUST_ID', $data['PST_CUST_ID'])
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
                    'A_MASTER_WORK' => null,
                    'A_COPY_WORK' => null,
                    'A_NO_WORK' => null,
                    'A_MOI_WORK' => null,
                    'A_VILLAGE_WORK' => null,
                    'A_BUILDING_WORK' => null,
                    'A_FLOOR_WORK' => null,
                    'A_ROOM_NO_WORK' => null,
                    'A_SOI_WORK' => null,
                    'A_ROAD_WORK' => null,
                    'A_PROVINCE_WORK' => null,
                    'A_DISTRICT_WORK' => null,
                    'A_SUBDISTRICT_WORK' => null,
                    'A_POSTALCODE_WORK' => null,
                    'A_OWNER_TYPE_WORK' => null,
                    'A_LIVEING_TIME_WORK' => null,
                    'A_PHONE_WORK' => null,
                    'A_LATITUDE_WORK' => null,
                    'A_LONGITUDE_WORK' => null,
                    'A_NAME_WORK' => null,
                ]);

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => [
                    'QUOTATION_ID' => $data['QUOTATION_ID'],
                    'PST_CUST_ID' => $data['PST_CUST_ID']
                ]
            ));
        } catch (Exception $e) {

            return $this->Error_Exception->Msg_error($e);
        }
    }
}
