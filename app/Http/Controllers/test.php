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
use stdClass;

use App\Http\Controllers\Check_Calculator;

class test extends BaseController
{

    public function __construct()
    {
        // dd('456');
    }

    public function ppvv(Request $request)
    {
        $data =  $request->all();

        $obj = array();
        // dd($json_P);
        $MT_PROVINCE = DB::table('dbo.MT_PROVINCE')
            ->select('*')
            ->get();
        // dd($MT_PROVINCE);

        foreach ($MT_PROVINCE as $value) {
            array_push($obj, (object) [
                'PROVINCE_ID' => $value->PROVINCE_ID,
                'PROVINCE_NAME' => $value->PROVINCE_NAME,
                'DISTRICT' => [],
            ]);
        }
        // dd($obj);
        foreach ($obj as $key => $value) {
            // dd($value->PROVINCE_ID);
            $MT_DISTRICT = DB::table('dbo.MT_DISTRICT')
                ->select('*')
                ->leftJoin('MT_PROVINCE', 'MT_DISTRICT.PROVINCE_ID', '=', 'MT_PROVINCE.PROVINCE_ID')
                ->where('MT_DISTRICT.PROVINCE_ID', $value->PROVINCE_ID)
                ->get();
            // dd($MT_DISTRICT);
            // var_dump('<pre>');
            // var_dump($obj[$key]->amphoes);
            // var_dump('<pre>');
            $obj_DISTRICT = array();
            foreach ($MT_DISTRICT as $DIS_key => $DIS_value) {
                array_push($obj_DISTRICT, (object) [
                    'DISTRICT_ID' => $DIS_value->DISTRICT_ID,
                    'DISTRICT_NAME' => $DIS_value->DISTRICT_NAME,
                    'SUB_DISTRICT' => array(),
                ]);
            }

            foreach ($obj_DISTRICT as $obj_DIS_key => $obj_DIS_value) {
                // dd($obj_DIS_value);
                $MT_SUB_DISTRICT = DB::table('dbo.MT_SUB_DISTRICT')
                    ->select('*')
                    ->leftJoin('MT_POST_CODE', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID', '=', 'MT_POST_CODE.SUB_DISTRICT_ID')
                    ->where('MT_SUB_DISTRICT.DISTRICT_ID', $obj_DIS_value->DISTRICT_ID)
                    ->get();
                $obj_SUB_DISTRICT = array();
                foreach ($MT_SUB_DISTRICT as $SUB_DIS_key => $SUB_DIS_value) {
                    array_push($obj_SUB_DISTRICT, (object) [
                        'SUB_DISTRICT_ID' => $SUB_DIS_value->SUB_DISTRICT_ID,
                        'SUB_DISTRICT_NAME' => $SUB_DIS_value->SUB_DISTRICT_NAME,
                        'POST_CODE_ID' => $SUB_DIS_value->POST_CODE_ID,
                    ]);
                }
                array_push($obj_DISTRICT[$obj_DIS_key]->SUB_DISTRICT, $obj_SUB_DISTRICT);
            }
            // dd($obj_DISTRICT);
            array_push($obj[$key]->DISTRICT, $obj_DISTRICT);
        }

        // dd($obj);
        // return json_encode($obj, JSON_FORCE_OBJECT);
        return $obj;
    }

    public function file(Request $request)
    {
        try {
            $data =  $request->all();
            $file = json_decode(file_get_contents($data['file']), true);
            // dd($file);
            $amper = array();
            $obj = array();
            // dd($file[0]['amphoes'][5]);

            // dd($file);
            foreach ($file as $index => $val) {
                array_push($obj, (object)[
                    'PROVINCE_ID' => $val['id'],
                    'PROVINCE_NAME' => $val['name'],
                    'PROVINCE_NAME_EN' => $val['name_en'],
                ]);
            }

            // foreach ($file as $index => $val) {
            //     array_push($amper, $val['amphoes']);
            //     // dd($val['amphoes']);
            //     // var_dump( isset($val['amphoes'][$index]["name"]) ? $val['amphoes'][$index]["name"] : 'error' );
            //     // array_push($obj, (object)[
            //     //     'PROVINCE_ID' => $val['id'],
            //     //     'DISTRICT_ID' => $val['amphoes'][$index]["id"],
            //     //     'DISTRICT_NAME' => $val['amphoes'][$index]["name"],
            //     //     'DISTRICT_EN' => $val['amphoes'][$index]["name_en"],
            //     // ]);
            // }

            // -----------------------------------------------------------------------------------

            // for ($i = 0; $i < count($file); $i++) {
            //     // dd($file[$i]);
            //     for ($a = 0; $a < count($file[$i]['amphoes']); $a++) {
            //         // dd($file[$i]['amphoes']);
            //         // var_dump(count($file[$i]['amphoes'][$a]['districts']));
            //         for ($j = 0; $j < count($file[$i]['amphoes'][$a]['districts']); $j++) {
            //             if (count($file[$i]['amphoes'][$a]['districts'][$j]['zipcode']) != 0) {
            //                 for ($z = 0; $z < count($file[$i]['amphoes'][$a]['districts'][$j]['zipcode']); $z++) {
            //                     // var_dump($file[$i]['amphoes'][$a]['districts'][$j]['zipcode'][$z]);
            //                     array_push($obj, (object)[
            //                         'SUB_DISTRICT_ID' => $file[$i]['amphoes'][$a]['districts'][$j]['id'],
            //                         'SUB_DISTRICT_NAME' => $file[$i]['amphoes'][$a]['districts'][$j]['name'],
            //                         'POST_CODE_ID' => $file[$i]['amphoes'][$a]['districts'][$j]['zipcode'][$z],
            //                     ]);
            //                 }
            //                 // var_dump($file[$i]['amphoes'][$a]['districts'][$j]['zipcodemain']);
            //             } else {
            //                 array_push($obj, (object)[
            //                     'SUB_DISTRICT_ID' => $file[$i]['amphoes'][$a]['districts'][$j]['id'],
            //                     'SUB_DISTRICT_NAME' => $file[$i]['amphoes'][$a]['districts'][$j]['name'],
            //                     'POST_CODE_ID' => $file[$i]['amphoes'][$a]['districts'][$j]['zipcodemain'],
            //                 ]);
            //             }
            //         }
            //     }
            //     // dd(($obj);
            // }
            return json_encode($obj);
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '00X2',
                'status' => 'Error',
                'message' => $e->getMessage(),
            ));
        }
    }

    public function simulater_working(Request $request){
        try{

            $data = $request->all();

            if(!isset($data['test'])){
                throw new Exception('456');
            }

            $obj = new stdClass();
            $obj->obj_15 = '123';

            return $obj;

        }catch(Exception $e){
            return response()->json(array(
                'Code' => 'TT0010',
                'test-01' => 'tt',
            ));
        }
        
    }

    public function Cal_EFFECTIVE(Request $request)
    {
        // $nper = 12;
        // $pmt = 1676.64;
        $data = $request->all();
        $nper = $data['INSTALL_NUM'];
        $pmt = $data['INSTALL_AMT'];
        $pv = -$data['HP_AMT']; 
        $fv = 0;
        $type = 0;
        $guess = 0.1;
        // var_dump(($this->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess)*12)*100);
        // $EFFECTIVE = strval(round($this->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12, 8));
        $Check_Calculator = new Check_Calculator;
        $EFFECTIVE = strval(round($Check_Calculator->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12, 8));
        return $EFFECTIVE;
    }
}
