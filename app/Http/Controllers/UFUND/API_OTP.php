<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\CustomException;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class API_OTP extends BaseController
{

    protected $MAILBIT_APIKey;
    protected $MAILBIT_ClientID;
    protected $MAILBIT_SenderId;

    protected $NIPAMAIL_TokenKey;

    public function __construct()
    {
        $this->MAILBIT_APIKey = ENV('MAILBIT_APIKey');
        $this->MAILBIT_ClientID = ENV('MAILBIT_ClientID');
        $this->NIPAMAIL_TokenKey = ENV('NIPAMAIL_TokenKey');
    }


    function generateRandomRef($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ref = '';
        for ($i = 0; $i < $length; $i++) {
            $ref .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $ref;
    }

    function PostRequest_SMS($_data)
    {
        $url = "https://api.send-sms.in.th/api/v2/SendSMS";

        $response = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post($url, $_data);
        $resData =  $response->body();

        return array($resData, $_data['message']);
    }


    function PostRequest_EMail($_data)
    {
        $url = "https://app-a.nipamail.com/v1.0/transactional/post/json?accept_token={$this->NIPAMAIL_TokenKey}";
        dd($_data);
        $response = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post($url, $_data);
        $resData =  $response->body();

        return $resData;
    }


    function SendSMS_OTP()
    {
        try {
            $expiryTime = Carbon::now()->addMinutes(5);
            $ref = $this->generateRandomRef();
            $phone = '66804817163';

            $otp_code = mt_rand(100000, 999999);
            $message = "รหัสของคุณคือ {$otp_code} <Ref.{$ref}>";

            $data_arry = array(
                'apiKey' => $this->MAILBIT_APIKey,
                'clientId' => $this->MAILBIT_ClientID,
                'mobileNumbers' => $phone,
                'SenderId' => $this->MAILBIT_SenderId,
                'message' => $message,
                'is_Unicode' => true,
                'is_Flash' => false,
            );

            // list($content, $txt_message) = $this->PostRequest_SMS($data_arry);
            // $obj2 = json_decode($content);

            $responseData = [
                'code' => '0000',
                'status' => 'Success',
                'message' => [
                    'OTP' => $otp_code,
                    'Ref' => $ref,
                    'Expire' =>  $expiryTime->toDateTimeString(),
                    // 'Expire' =>  $expiryTime->timestamp,
                ]
            ];

            return response()->json($responseData);
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }


    function SendEMail_OTP()
    {
        try {
            $expiryTime = Carbon::now()->addMinutes(5);
            $ref = $this->generateRandomRef();
            $Email = 'kid00345@hotmail.com';

            $otp_code = mt_rand(100000, 999999);
            $message = "รหัสของคุณคือ {$otp_code} <Ref.{$ref}>";

            $data_array = array(
                'from_name' => "UFUND",
                'from_email' => "info@Thunderfinfin.com",
                'to' => $Email,
                'subject' => "OTP",
                'message' => $message,
            );

            // $res = $this->PostRequest_EMail($data_array);
            // $json_res = json_decode($res);
            
            $responseData = [
                'code' => '0000',
                'status' => 'Success',
                'message' => [
                    'OTP' => $otp_code,
                    'Ref' => $ref,
                    'Expire' =>  $expiryTime->toDateTimeString(),
                    // 'Expire' =>  $expiryTime->timestamp,
                ]
            ];

            return response()->json($responseData);
            
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }
}
