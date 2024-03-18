<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ASSETS_INFORMATION_REF;


class MT_INSURE extends Model
{
    protected $table = 'dbo.MT_INSURE';

    use HasFactory;

    protected $fillable = [
        'INSURE_ID',
        'INSURE_CODE',
        'INSURE_COM_ID',
        'INSURE_BRAND_ID',
        'INSURE_PRODUCT_CODE',
        'INSURE_PRODUCT_NAME',
        'INSURE_PRICE',
        'INSURE_PRICE_PREMIUM',
        'STANDARD_REBATE',
        'EXTRA_REBATE',
        'INSURE_MARGIN',
        'INSURE_YEARS',
        'SERIES_ID',
        'START_DATE',
        'END_DATE',
        'ACTIVE_STATUS',
    ];
}
