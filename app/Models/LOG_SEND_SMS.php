<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LOG_SEND_SMS extends Model
{
    protected $table = 'LOG_SEND_SMS';

    public $timestamps = false;
    
    use HasFactory;

    protected $fillable = [
        "SMS_ID",
        "DATE",
        "RUNNING_NO",
        "CONTRACT_ID",
        "QUOTATION_ID",
        "APP_ID",
        "TRANSECTION_TYPE",
        "TRANSECTION_ID",
        "DUE_DATE",
        "SEND_DATE",
        "SEND_TIME",
        "SEND_Phone",
        "SMS_RESPONSE_CODE",
        "SMS_RESPONSE_MESSAGE",
        "SMS_RESPONSE_JOB_ID",
        "SMS_RESPONSE_MSG_ID",
        "SMS_TEXT_MESSAGE",
        "SMS_CREDIT_USED",
        "SMS_Status_Delivery",
        "USER_SEND",
    ];
}
