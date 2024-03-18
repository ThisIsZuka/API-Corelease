<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_BRAND extends Model
{
    protected $table = 'MT_BRAND';

    use HasFactory;

    protected $fillable = [
        'BRAND_ID',
        'BRAND_CODE',
        'BRAND_NAME',
        'PRODUCT_CATEGORY_ID',
        'PRODUCT_CATEGORY_NAME',
        'ACTIVE_STATUS',
    ];

}
