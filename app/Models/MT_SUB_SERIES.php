<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_SUB_SERIES extends Model
{
    protected $table = 'MT_SUB_SERIES';

    use HasFactory;

    protected $fillable = [
        'SUB_SERIES_ID',
        'SUB_SERIES_CODE',
        'SUB_SERIES_NAME',
        'SERIES_ID',
        'SERIES_NAME',
        'PRODUCT_PRICE',
        'ACTIVE_STATUS',
    ];

}
