<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CONTRACT extends Model
{
    protected $table = 'CONTRACT';

    use HasFactory;

    protected $fillable = [
        "CONTRACT_ID",
        "STATUS_ID",
        "STATUS_HP",
        "APP_ID",
        "PERSON_ID",
        "PARTNER_ID",
        "P_BRANCH_ID",
        "EMP_ID",
        "CIF_PERSON_ID",
        "PRODUDCT_ID",
        "REPAY_ID",
        "APPLICATION_NUMBER",
        "CONTRACT_NUMBER",
        "CUSTOMER_NAME",
        "MAKE_DATE",
        "CONTRACT_START",
        "CONTRACT_END",
        "PERIOD_DATE",
        "INSTALL_NUM_FINAL",
        "OVERDUE",
        "ASSIGN_DATE",
        "COLLECTION_NAME",
        "ROLE_COLLECTION",
        "SERIAL_NUMBER",
        "CREATE_DATE",
        "UPDATE_DATE",
        "NAME_MAKE"
    ];

}
