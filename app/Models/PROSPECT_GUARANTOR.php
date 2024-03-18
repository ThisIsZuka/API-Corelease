<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PROSPECT_GUARANTOR extends Model
{
    protected $table = 'PROSPECT_GUARANTOR';

    public $timestamps = false;

    use HasFactory;

    protected $primaryKey = 'PST_GUAR_ID';

    protected $fillable = [
        'PST_GUAR_ID',
        'QUOTATION_ID',
        'PREFIX',
        'PREFIX_OTHER',
        'FIRST_NAME',
        'LAST_NAME',
        'SEX',
        'MARITAL_STATUS',
        'BIRTHDAY',
        'AGE',
        'TAX_ID',
        'REGIS_TAX_DATE',
        'EXPIRE_TAX_DATE',
        'PHONE',
        'MOBILE',
        'EMAIL',
        'LINEID',
        'FACEBOOK',
        'OCCUPATION_ID',
        'OFFICE_NAME',
        'OFFICE_POSITION',
        'WORK_YEAR',
        'WORK_MONTH',
        'MAIN_INCOME',
        'EXTRA_INCOME',
        'OFFICE_PHONE',
        'RELATION_REF',
        'RELATION_REF_DES',
        'IDCARD_FILE',
        'SLIP_FILE',
        'STATEMENT_FILE',
        'OTHER_FILE',
        'ACCEPT_STATUS',
        'RESULT_GUARANTOR',
        'ACTIVE_STATUS',
        'CREATE_DATE',
        'UPDATE_DATE',
        'NAME_MAKE',
    ];
}
