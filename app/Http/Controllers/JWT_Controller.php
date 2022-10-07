<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use ReallySimpleJWT\Token;

class JWT_Controller extends BaseController
{
    public function Get_Token(Request $request)
    {
        try {

            $data = $request->all();

            if(!isset($data['username'])) throw new Exception('Required Parameter [username]');
            if(!isset($data['password'])) throw new Exception('Required Parameter [password]');

            if($data['username'] != "api_ufund" || $data['password'] != "U6undp0Rt4l"){
                throw new Exception('Invalid Username or Password');
            }

            date_default_timezone_set("Asia/Bangkok");
            $now = Carbon::now()->timestamp;
            $exp = Carbon::now()->addHours(8)->timestamp;
            $payload = [
                'iat' => $now,
                'uid' => 1,
                'exp' => $exp,
                'iss' => 'ufundportal.com',
                'user' => $data['username']
            ];

            $secret = ENV('JWT_SECRET');
            // dd($secret);

            $token = Token::customPayload($payload, $secret);

            $data_token = (array(
                'user' => $data['username'],
                'expire' => 3600, 
                'api-token' => $token,
            ));
            

            return response()->json(array(
                'Code' => '9999',
                'status' => 'Sucsess', 
                'data' => $data_token,
            ));

        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '00X2',
                'status' => 'Error', 
                'message' => $e->getMessage(),
            ));
        }
    }
}
