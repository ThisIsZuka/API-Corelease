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

use App\Models\MT_SMS_OTP;
use App\Models\MT_EMAIL_OTP;
use App\Models\LOG_SEND_SMS;
use App\Models\LOGGED_EMAIL_HEADER;
use App\Models\LOGGED_EMAIL_LISTS;

class ApiOtpControllers extends BaseController
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
        $this->MAILBIT_SenderId = ENV('MAILBIT_SenderId');
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

        // handle successful response
        if ($response->successful()) {
            $resData = $response->body();
        } else { // handle error
            if ($response->clientError()) {
                // The response has a 4xx status code
                $resData =  $response->clientError();
            } elseif ($response->serverError()) {
                // The response has a 5xx status code
                $resData =  $response->serverError();
            }
        }

        // return array($resData, $_data['message']);
        return $resData;
    }


    function PostRequest_EMail($_data)
    {
        $url = "https://app-a.nipamail.com/v1.0/transactional/post/json?accept_token={$this->NIPAMAIL_TokenKey}";

        $response = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post($url, $_data);

        // handle successful response
        if ($response->successful()) {
            $resData = $response->body();
        } else { // handle error
            if ($response->clientError()) {
                // The response has a 4xx status code
                $resData =  $response->clientError();
            } elseif ($response->serverError()) {
                // The response has a 5xx status code
                $resData =  $response->serverError();
            }
        }

        return $resData;
    }

    function SendSMS_OTP(Request $request)
    {

        $is_pass = true;
        $ref = $this->generateRandomRef();
        $phone = $request->phone;
        $api_data = $response_sms = array();
        $otp_code = 0;
        $MessageId = $set_new_phone = '';
        DB::beginTransaction();

        try {
            /// check mobile number not null .
            if ($is_pass) {
                $cus_phone = $phone;
                $cus_phone = (implode(explode("-", $cus_phone))); //remove - 
                if ($cus_phone == '') {
                    $is_pass = false;
                    $responseData = [
                        'code' => '2000',
                        'status' => 'Invalid condition',
                        'message' => 'Cannot found Mobile number.'
                    ];
                    return response()->json($responseData);
                }
            }

            /// check mobile number 10 digi .
            if ($is_pass) {
                if (strlen($phone) != 10) {
                    $is_pass = false;
                    $responseData = [
                        'code' => '2000',
                        'status' => 'Invalid condition',
                        'message' => 'Mobile number is incorrect. '
                    ];
                    return response()->json($responseData);
                } /// end if phone strlen == 10 
            }

            /// check mobile number.
            if ($is_pass) {
                //ignore about first letter , ex : 0, +66, 02 Etc. 
                if (substr($cus_phone, 0, 2) == 02) {
                    $cus_phone = substr($cus_phone, -8, 8); //ex : 021234567 => 21234567
                } else {
                    $cus_phone = substr($cus_phone, -9, 9); //reverse mobile number. ex: +66123456789 => 123456789
                }

                $otp_code = mt_rand(100000, 999999);
                $message = "รหัสของคุณคือ {$otp_code} <Ref.{$ref}>";
                $set_new_phone = "66" . $cus_phone;
                $api_data = array(
                    'apiKey' => $this->MAILBIT_APIKey,
                    'clientId' => $this->MAILBIT_ClientID,
                    'mobileNumbers' => $set_new_phone,
                    'SenderId' => $this->MAILBIT_SenderId,
                    'message' => $message,
                    'is_Unicode' => true,
                    'is_Flash' => false,
                );
            }

            /// นับจำนวนให้สามารถส่งติดต่อกันได้มากสุด 3 ครั้ง,
            if ($is_pass) {

                $limit = 5;
                $sum = 0;
                $now = Carbon::now();
                // echo $now->toDateString(); YYYY/MM/DD
                $count_sms = MT_SMS_OTP::WHERE('MOBILE_NO', $set_new_phone)->whereBetween('CREATED_AT', [$now->toDateString() . " 00:00:00",  $now->toDateString() . " 23:59:59"])->orderBy('CREATED_AT', 'DESC')->limit($limit)->get();

                foreach ($count_sms as $key => $value) {

                    if ($key == 0) {
                        $date_created_at = explode(".", $value->CREATED_AT)[0];
                        $anchorTime = Carbon::now();
                        $currentTime = Carbon::createFromFormat("Y-m-d H:i:s",  date("Y-m-d H:i:s", strtotime($date_created_at)));
                        $minuteDiff = $anchorTime->diffInMinutes($currentTime);
                    }

                    (!$value->STATUS) ? ++$sum : $sum = 0;
                }

                if ($sum >= 3) {
                    if ($minuteDiff >= 5) {
                        $is_pass = true;
                    } else {
                        $is_pass = false;
                        $responseData = [
                            'code' => '2000',
                            'status' => 'Invalid condition',
                            'message' => 'กดขอ otp เกินจำนวนครั้งที่กำหนด กรุณารอ 5 นาที'
                        ];
                        return response()->json($responseData);
                    }
                }
            }

            /// send api ,
            if ($is_pass) {

                $response = $this->PostRequest_SMS($api_data);
                $response_sms = json_decode($response);

                if ($response_sms->ErrorCode == '0') {
                    foreach ($response_sms->Data as $data) {
                        $MessageId = $data->MessageId;
                    }
                } else {
                    DB::rollBack();
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System SMS Error',
                        'message' => $response_sms->ErrorDescription
                    ];
                    return response()->json($responseData);
                }
            }

            /// insert db MT_SMS_OTP ,LOG_SEND_SMS
            if ($is_pass) {
                $mt_sms_otp = new MT_SMS_OTP();
                $mt_sms_otp->REF_CODE = $ref;
                $mt_sms_otp->OTP_CODE = $otp_code;
                $mt_sms_otp->MOBILE_NO = $set_new_phone;
                $mt_sms_otp->MESSAGE_ID = $MessageId;
                $mt_sms_otp->CREATED_AT = Carbon::now();
                $mt_sms_otp->EXPIRYDATE =  Carbon::now()->addMinutes(5);

                if (!$mt_sms_otp->save()) {
                    DB::rollBack();
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System Error',
                        'message' => 'Failed to create otp.'
                    ];
                    return response()->json($responseData);
                } else {

                    $MSG_ID = null;
                    $SumCredit = null;
                    if (!is_null($response_sms->Data)) {
                        $MSG_ID = $response_sms->Data[0]->MessageId;
                        $SumCredit = count($response_sms->Data);
                    }

                    $log_send_sms = new  LOG_SEND_SMS;
                    $log_send_sms->DATE = Carbon::now();
                    // $log_send_sms->RUNNING_NO = $new_id[0]->new_id;
                    // $log_send_sms->QUOTATION_ID = $list_sendSMS[0]->QUOTATION_ID,
                    // $log_send_sms->APP_ID = $list_sendSMS[0]->APP_ID,
                    $log_send_sms->TRANSECTION_TYPE = null;
                    $log_send_sms->TRANSECTION_ID = null;
                    $log_send_sms->SMS_RESPONSE_CODE = $response_sms->ErrorCode == 0 ? '000' : $response_sms->ErrorCode;
                    $log_send_sms->SMS_RESPONSE_MESSAGE = $response_sms->ErrorCode == 0 ? 'Success' : $response_sms->ErrorDescription;
                    // $log_send_sms->'SMS_RESPONSE_JOB_ID' = $obj2->JobId,
                    $log_send_sms->SEND_DATE = date('Y-m-d');
                    $log_send_sms->SEND_TIME = date('H:i:s');
                    $log_send_sms->SEND_Phone = $set_new_phone;
                    // $log_send_sms->CONTRACT_ID = $list_sendSMS[0]->Contract_id,
                    // $log_send_sms->DUE_DATE = $DUE_DATE;
                    $log_send_sms->SMS_RESPONSE_MSG_ID = $MSG_ID;
                    $log_send_sms->SMS_TEXT_MESSAGE = $api_data['message'];
                    $log_send_sms->SMS_CREDIT_USED = $SumCredit;
                    // $log_send_sms->USER_SEND = $user_send;
                    $log_send_sms->save();

                    DB::commit();

                    $date_expire_at = explode(".", $mt_sms_otp->EXPIRYDATE)[0];
                    $responseData = [
                        'code' => '0000',
                        'status' => 'Success',
                        'message' => [
                            'Ref' => $ref,
                            'Expire' =>  Carbon::createFromFormat("Y-m-d H:i:s",  date("Y-m-d H:i:s", strtotime($date_expire_at))),
                        ]
                    ];
                    return response()->json($responseData);
                }
            }
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }

    function SendEMail_OTP(Request $request)
    {
        $is_pass = true;
        $expiryTime = Carbon::now()->addMinutes(5);
        $ref = $this->generateRandomRef();
        $Email = $request->email;
        // $Email = 'kid00345@hotmail.com';

        try {

            // $request->validate([
            //     'email' => 'required|email:rfc,dns|unique:users'
            // ]);


            /// set data , send api email
            if ($is_pass) {

                $otp_code = mt_rand(100000, 999999);
                $message = "รหัสของคุณคือ {$otp_code} <Ref.{$ref}>";

                $data_api = array(
                    'from_name' => "UFUND",
                    'from_email' => "info@Thunderfinfin.com",
                    'to' => $Email,
                    'subject' => "OTP",
                    'message' => $message,
                );

                $response = $this->PostRequest_EMail($data_api);

                $response_email = json_decode($response);
                // echo "<pre>";
                // print_r($response_email);
                // die;
                if ($response_email) {
                    $is_pass = true;
                } else {
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System Error',
                        'message' => 'Failed to send otp.'
                    ];
                }
            }


            // insert logs email
            if($is_pass){

                $log_send_email_header = new LOGGED_EMAIL_HEADER();
                // $log_send_email_header->id = null;
                $log_send_email_header->bulkId = $response_email->id;
                $log_send_email_header->code = $response_email->status_code;
                $log_send_email_header->message = $response_email->message;
                $log_send_email_header->send_date =  Carbon::now();
                $log_send_email_header->checked = 0;
                $log_send_email_header->save();
                
                // $log_send_email_list = new  LOGGED_EMAIL_LISTS();
                // $log_send_email_list->header_id = $log_send_email_header->id;
                // $log_send_email_list->message_bulkId = $response_email->bulkId;
                // $log_send_email_list->message_transId = $response_email->tranId;
                // $log_send_email_list->form = $response_email->from;
                // $log_send_email_list->to = $response_email->to;
                // $log_send_email_list->user_open = null;
                // $log_send_email_list->user_click = null;
                // $log_send_email_list->timestamp = null;
                // $log_send_email_list->status = null;
                // $log_send_email_list->errors = null;
                // $log_send_email_list->create_date = Carbon::now();
                // $log_send_email_list->update_date = null;
                // $log_send_email_list->save();
                
            }

            if ($is_pass) {

                $mt_email_otp = new MT_EMAIL_OTP();
                $mt_email_otp->REF_CODE = $ref;
                $mt_email_otp->OTP_CODE = $otp_code;
                $mt_email_otp->EMAIL = $Email;
                $mt_email_otp->MESSAGE_ID = $log_send_email_header->bulkId;
                $mt_email_otp->CREATED_AT = Carbon::now();
                $mt_email_otp->EXPIRYDATE =  Carbon::now()->addMinutes(5);
                if (!$mt_email_otp->save()) {
                    DB::rollBack();
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System Error',
                        'message' => 'Failed to create otp.'
                    ];
                } else {
                    
                    $date_expire_at = explode(".", $mt_email_otp->EXPIRYDATE)[0];
                    $responseData = [
                        'code' => '0000',
                        'status' => 'Success',
                        'message' => [
                            'Ref' => $ref,
                            'Expire' =>  Carbon::createFromFormat("Y-m-d H:i:s",  date("Y-m-d H:i:s", strtotime($date_expire_at))),
                        ]
                    ];

                    return response()->json($responseData);
                }

            } else {
                return response()->json(array(
                    'code' => '9000',
                    'status' => 'System Error',
                    'message' => 'API Error'

                ));
            }
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }

    function UsedSMS_OTP(Request $request)
    {

        $is_pass = true;
        DB::beginTransaction();
        $phone = $request->phone;
        $ref = $request->ref_code;
        $otp_code = $request->otp_code;
        $set_new_phone = '';

        try {

            /// check mobile number not null .
            if ($is_pass) {
                $cus_phone = $phone;
                $cus_phone = (implode(explode("-", $cus_phone))); //remove - 
                if ($cus_phone == '') {
                    $is_pass = false;
                    $responseData = [
                        'code' => '2000',
                        'status' => 'Invalid condition',
                        'message' => 'Cannot found Mobile number.'
                    ];
                    return response()->json($responseData);
                }
            }

            /// check mobile number 10 digi .
            if ($is_pass) {
                if (strlen($phone) != 10) {
                    $is_pass = false;
                    $responseData = [
                        'code' => '2000',
                        'status' => 'Invalid condition',
                        'message' => 'Mobile number is incorrect. '
                    ];
                    return response()->json($responseData);
                } else { /// end if phone strlen == 10 
                    //ignore about first letter , ex : 0, +66, 02 Etc. 
                    if (substr($cus_phone, 0, 2) == 02) {
                        $cus_phone = substr($cus_phone, -8, 8); //ex : 021234567 => 21234567
                    } else {
                        $cus_phone = substr($cus_phone, -9, 9); //reverse mobile number. ex: +66123456789 => 123456789
                    }

                    $set_new_phone = "66" . $cus_phone;
                }
            }

            $mt_sms_otp = MT_SMS_OTP::WHERE('MOBILE_NO', $set_new_phone)->WHERE('REF_CODE', $ref)->WHERE('STATUS', 0)->WHERE('OTP_CODE', $otp_code)->first();

            //check sms is true
            if ($mt_sms_otp == null) {
                $is_pass = false;
                return response()->json(array(
                    'code' => '9000',
                    'status' => 'SMS OTP was invalid.',
                    'message' => 'SMS OTP Error'
                ));
            }

            // check sms expirydate
            if ($is_pass) {

                $date_expiry_date = explode(".", $mt_sms_otp->EXPIRYDATE)[0];
                $currentTime = Carbon::createFromFormat("Y-m-d H:i:s",  date("Y-m-d H:i:s", strtotime($date_expiry_date)));
                if (Carbon::now() > $currentTime) {

                    $is_pass = false;

                    $responseData = [
                        'code' => '9000',
                        'status' => 'SMS OTP was expirydate',
                        'message' => 'otp ของคุณหมดอายุ กรุณากดขอใหม่อีกครั้ง',
                        'expiry' => true
                    ];
                    return response()->json($responseData);
                }
            }

            // update sms otp used
            if ($is_pass) {
                $mt_sms_otp = MT_SMS_OTP::WHERE('OTP_ID', $mt_sms_otp->OTP_ID)
                    ->update(array(
                        'STATUS' => 1,
                        'USED_AT' => Carbon::now(),
                        'UPDATED_AT' => Carbon::now(),
                    ));
                if (!$mt_sms_otp) {
                    DB::rollBack();
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System Error',
                        'message' => 'Failed to used otp.'
                    ];

                    return response()->json($responseData);
                } else {
                    DB::commit();
                    $responseData = [
                        'code' => '0000',
                        'status' => 'Success',
                    ];
                    return response()->json($responseData);
                }
            }
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }

    function UsedEmail_OTP(Request $request)
    {

        $is_pass = true;
        DB::beginTransaction();
        $email = $request->email;
        $ref = $request->ref;
        $otp_code = $request->otp_code;

        try {

            $mt_email_otp = MT_EMAIL_OTP::WHERE('EMAIL', $email)->WHERE('REF_CODE', $ref)->WHERE('STATUS', 0)->WHERE('OTP_CODE', $otp_code)->first();

            //check sms is true
            if ($mt_email_otp == null) {
                $is_pass = false;
                return response()->json(array(
                    'code' => '9000',
                    'status' => 'OTP was invalid.',
                    'message' => 'OTP Error'
                ));
            }

            // check sms expirydate
            if ($is_pass) {
                $date_expiry_date = explode(".", $mt_email_otp->EXPIRYDATE)[0];
                $currentTime = Carbon::createFromFormat("Y-m-d H:i:s",  date("Y-m-d H:i:s", strtotime($date_expiry_date)));
                if (Carbon::now() > $currentTime) {
                    
                    $is_pass = false;
                    
                    $responseData = [
                        'code' => '9000',
                        'status' => 'SMS OTP was expirydate',
                        'message' => 'otp ของคุณหมดอายุ กรุณากดขอใหม่อีกครั้ง',
                        'expiry' => true
                    ];
                    return response()->json($responseData);
                }
            }

            // update sms otp used
            if ($is_pass) {

                    $mt_email_otp = MT_EMAIL_OTP::WHERE('OTP_ID', $mt_email_otp->OTP_ID)
                    ->update(array(
                        'STATUS' => 1,
                        'USED_AT' => Carbon::now(),
                        'UPDATED_AT' => Carbon::now(),
                    ));

                if (!$mt_email_otp) {
                    DB::rollBack();
                    $is_pass = false;
                    $responseData = [
                        'code' => '9000',
                        'status' => 'System Error',
                        'message' => 'Failed to update otp.'
                    ];
                    return response()->json($responseData);
                } else {

                    DB::commit();

                    $responseData = [
                        'code' => '0000',
                        'status' => 'Success',
                    ];

                    return response()->json($responseData);
                }
            }
        } catch (Exception $e) {

            return response()->json(array(
                'code' => '9000',
                'status' => 'System Error',
                'message' => $e->getMessage()

            ));
        }
    }
}
