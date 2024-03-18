<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_SMS_OTP extends Model
{
    protected $table = 'MT_SMS_OTP';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        "OTP_ID",
        "REF_CODE",
        "STATUS",
        "OTP_CODE",
        "MOBILE_NO",
        "CREATED_AT",
        "UPDATED_AT",
        "EXPIRYDATE",
        "USED_AT",
        "MESSAGE_ID",
        "SCB_REF_NO",
    ];

}
