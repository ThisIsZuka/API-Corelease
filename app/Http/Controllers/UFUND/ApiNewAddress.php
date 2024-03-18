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

use App\Models\ADDRESS;

class ApiNewAddress extends BaseController
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

    public function New_Address(Request $request)
    {
        try {

            $data = $request->all();

            $ADDRESS = new ADDRESS([
                // 'PERSON_ID' => null,
                // 'CIF_PERSON_ID' => null,
                // 'JURISTIC_ID' => null,
                'QUOTATION_ID' => $data['QUOTATION_ID'],
                'A1_MASTER' => 1,
                'A1_COPY' => null,
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
                // 'A1_LATITUDE' => null,
                // 'A1_LONGITUDE' => null,
                'A2_MASTER' => 2,
                'A2_COPY' => null,
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
                // 'A2_LATITUDE' => null,
                // 'A2_LONGITUDE' => null,
                'A3_MASTER' => 3,
                'A3_COPY' => null,
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
                // 'A3_LATITUDE' => null,
                // 'A3_LONGITUDE' => null,
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
                'A4_MASTER' => null,
                'A4_COPY' => null,
                'A4_NO' => null,
                'A4_MOI' => null,
                'A4_VILLAGE' => null,
                'A4_BUILDING' => null,
                'A4_FLOOR' => null,
                'A4_ROOM_NO' => null,
                'A4_SOI' => null,
                'A4_ROAD' => null,
                'A4_PROVINCE' => null,
                'A4_DISTRICT' => null,
                'A4_SUBDISTRICT' => null,
                'A4_POSTALCODE' => null,
                'A4_OWNER_TYPE' => null,
                'A4_LIVEING_TIME' => null,
                'A4_PHONE' => null,
                'A4_LATITUDE' => null,
                'A4_LONGITUDE' => null,
                'A_NAME_WORK' => null,
            ]);

            $ADDRESS->save();

            $request->request->add(
                [
                    'ADDRESS_ID' => $ADDRESS->ADDRESS_ID,
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
