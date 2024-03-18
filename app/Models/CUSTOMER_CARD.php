<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CUSTOMER_CARD extends Model
{
    protected $table = 'CUSTOMER_CARD';

    use HasFactory;

    protected $fillable = [
        "ID",
        "CONTRACT_ID",
        "CONTRACT_NUMBER",
        "APPLICATION_NUMBER",
        "INSTALL_NUM",
        "DUEDATE",
        "INSTALL_AMT",
        "PAY_PRINCIPLE",
        "PAY_INTEREST",
        "PAY_INSTALL_VAT",
        "OUTSTD_SUM_PRINCIPLE",
        "OUTSTD_SUM_INTEREST",
        "DISCOUNT_AMT",
        "INVOICE_NUMBER",
        "RECEIPT_NUMBER",
        "SUM_OUTSTAND",
        "INSTALL_OD_01",
        "INSTALL_OD_02",
        "INSTALL_OD_SUM",
        "SUM_OD_AMT",
        "PENALTY_AMT",
        "COLLECT_AMT",
        "REVENUE_INS_MARGIN",
        "REVENUE_INS_MARGIN_OUTSTD",
        "FEE_INSTALL",
        "FEE_SUM",
    ];

}
