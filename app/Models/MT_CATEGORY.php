<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_CATEGORY extends Model
{
    protected $table = 'MT_CATEGORY';

    use HasFactory;

    protected $fillable = [
        'CATEGORY_ID',
        'CATEGORY_CODE',
        'CATEGORY_NAME',
        'GROUP_CATE_ID',
        'BUNDLE_WARRANTY_STATUS',
        'ACTIVE_STATUS',

    ];

}


