<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PROSPECT_CUSTOMER extends Model
{
    protected $table = 'PROSPECT_CUSTOMER';

    public $timestamps = false;

    use HasFactory;

    protected $primaryKey = 'PST_CUST_ID';

    protected $fillable = [
        'PST_CUST_ID',
        'QUOTATION_ID',
        'PREFIX',
        'PREFIX_ENG',
        'PREFIX_OTHER',
        'FIRST_NAME',
        'FIRST_NAME_ENG',
        'LAST_NAME',
        'LAST_NAME_ENG',
        'TAX_ID',
        'IDCARD_REGIS_DATE',
        'IDCARD_EXPIRE_DATE',
        'NATIONALITY_CODE',
        'STUDENT_ID',
        'BIRTHDAY',
        'AGE',
        'SEX',
        'MARITAL_STATUS',
        'PHONE',
        'FACEBOOK',
        'LINEID',
        'EMAIL',
        'OCCUPATION_CODE',
        'OCCUPATION_TYPE_CODE',
        'BUSINESSTYPE_CODE',
        'MAIN_INCOME',
        'OTHER_INCOME',
        'AMOUNT_INCOME',
        'UNIVERSITY_NAME',
        'UNIVERSITY_OTHER',
        'CAMPUS_NAME',
        'FACULTY_NAME',
        'FACULTY_OTHER',
        'SUBJECT_NAME',
        'LEVEL_TYPE',
        'U_LEVEL',
        'LOAN_KYS',
        'OFFICE_NAME',
        'OFFICE_YEAR',
        'OFFICE_MONTH',
        'REF_TITLE',
        'REF_TITLE_OTHER',
        'REF_FIRSTNAME',
        'REF_LASTNAME',
        'RELATION_REFERENCE',
        'RELATION_REF_DES',
        'REF_OCCUPATION',
        'REF_AGE',
        'REF_PHONE',
        'IDCARD_FILE',
        'IDCARD_FILE_SECOND',
        'STUDENTCARD_FILE',
        'BANK_STATE_FILE',
        'BANK_STATE_FILE_SECOND',
        'BANK_STATE_FILE_THIRD',
        'BANK_STATE_FILE_FOURTH',
        'FACE_FILE',
        'FACE_FILE_SECOND',
        'STATEMENT_FILE_THIRD',
        'STATEMENT_FILE_SECOND',
        'STATEMENT_FILE',
        'URLMAP',
        'CREATE_DATE',
        'UPDATE_DATE',
        'NAME_MAKE',
        'REF_TAX_ID',
        'UNIVERSITY_PROVINCE',
        'UNIVERSITY_DISTRICT',
        'REF_BIRTHDAY',
        'PHONE_SECOND',
        'REF_PHONE_SECOND',
        'Disease_ID',
        'Narcotic_ID',
        'APPROVE_CUSTOMER',
        'CREDIT_CARD',
        'FINANCIAL_AMOUNT',
        'FINANCIAL_AMOUNT_TRUE',
        'INSTITUTION_BANK',
        'INSTITUTION_BANK_AMOUNT',
        'Acknowledge_ID',
        'Balloon_Type',
        'REF_TAX_ID_2',
        'REF_TITLE_2',
        'REF_TITLE_OTHER_2',
        'RELATION_REFERENCE_2',
        'RELATION_REF_DES_2',
        'REF_OCCUPATION_2',
        'REF_AGE_2',
        'REF_PHONE_2',
        'REF_BIRTHDAY_2',
        'REF_PHONE_SECOND_2',
        'REF_FIRSTNAME_2',
        'REF_LASTNAME_2',
    ];
}
