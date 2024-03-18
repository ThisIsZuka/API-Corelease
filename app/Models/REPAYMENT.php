<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class REPAYMENT extends Model
{
    protected $table = 'REPAYMENT';

    public $timestamps = false;

    use HasFactory;

    protected $primaryKey = 'REPAY_ID';

    protected $fillable = [
        "REPAY_ID",
        "CONTRACT_ID",
        "CUSTOMER_CARD_ID",
        "APP_ID",
        "INVOICE_ID",
        "STATUS_ID",
        "APPLICATION_NUMBER",
        "CONTRACT_NUMBER",
        "CUSTOMER_NAME",
        "REPAY_TYPE",
        "RECEIPT_NUMBER",
        "TAX_NUMBER",
        "PHY_NUMBER",
        "CREDIT_NOTE_NUMBER",
        "PAY_TYPE",
        "MAKE_DATE",
        "REPAY_DATE",
        "REPAY_NAME",
        "REPAY_PENALTY",
        "REPAY_COLLECT",
        "REPAY_AMOUNT",
        "REPAY_WHT",
        "REPAY_VAT",
        "REPAY_DISCOUNT",
        "REPAY_SUM_AMOUNT",
        "DES_SUM_AMT",
        "PAY_DATE",
        "PAY_NAME",
        "PAY_PENALTY",
        "PAY_COLLECT",
        "PAY_AMT",
        "PAY_VAT",
        "PAY_DISCOUNT",
        "PAY_SUM_AMT",
        "INSTALL",
        "OVER_AMT",
        "LACK_AMT",
        "PENALTY_WAVE_AMT",
        "COLLECT_WAVE_AMT",
        "RESERVE_AMOUNT",
        "FLAG_RESERVE",
        "USE_RESERVE_AMT",
        "FLAG_EARLY_CLOSE",
        "CREATE_DATE",
        "UPDATE_DATE",
        "NAME_MAKE",
    ];
}
