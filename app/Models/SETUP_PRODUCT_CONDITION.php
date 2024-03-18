<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SETUP_PRODUCT_CONDITION extends Model
{
    protected $table = 'SETUP_PRODUCT_CONDITION';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'HP_PRODUCT_ID',
        'PROD_CODE',
        'PRODUCT_TYPE',
        'PROJECT_NAME',
        'OCCUPATION_ID',
        'UNIVERSITY_ID',
        'CAMPUS_ID',
        'FACULTY_ID',
        'U_LEVEL',
        'PRODUCT_CATEGORY',
        'PRODUCT_BAND',
        'PRODUCT_SERIES',
        'PRODUCT_SUB_SERIES',
        'EFFECTIVE_DATE',
        'END_DATE',
        'NOTICE_EMAIL',
        'NOTICE_MONTH',
        'REMARK',
        'STATUS_ID',
        'CREATE_DATE',
        'UPDATE_DATE',
        'NAME_MAKE',
    ];
}
