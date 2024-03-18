<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class APPLICATION extends Model
{
    protected $table = 'APPLICATION';

    public $timestamps = false;

    use HasFactory;

    protected $primaryKey = 'APP_ID';

    protected $fillable = [
        'APP_ID',
        'STATUS_ID',
        'APPLICATION_NUMBER',
        'APP_DATE',
        'CUSTOMER_NAME',
        'CIF_PERSON_ID',
        'PERSON_ID',
        'JURISTIC_ID',
        'PARTNER_ID',
        'P_BRANCH_TYPE',
        'P_BRANCH_ID',
        'PRODUCT_ID',
        'CHECKER_ID',
        'CHECKER_RESULT',
        'APPROVE_ID',
        'SCORING',
        'EMP_ID',
        'EMP_ID_Global',
        'EMP_ComCode',
        'QUOTATION_ID',
        'CREATE_DATE',
        'UPDATE_DATE',
        'NAME_MAKE',
    ];
}
