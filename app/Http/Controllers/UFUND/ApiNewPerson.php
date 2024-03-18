<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Http\Controllers\UFUND\Error_Exception;

use App\Models\APPLICATION;
use App\Models\SETUP_COMPANY_BRANCH;
use App\Models\PERSON;
use App\Models\PROSPECT_CUSTOMER;
use App\Models\QUOTATION;
use App\Models\ADDRESS;

class ApiNewPerson extends BaseController
{
    public $Error_Exception;
    public $DateStr;
    public $Date;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
        $this->DateStr = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
        $this->Date = Carbon::now(new DateTimeZone('Asia/Bangkok'));
    }

    public function New_Person(Request $request)
    {
        try {

            $data = $request->all();

            $PROSPECT_CUSTOMER = PROSPECT_CUSTOMER::where('QUOTATION_ID', $data['QUOTATION_ID'])->first();

            $QUOTATION = QUOTATION::where('QUOTATION_ID', $data['QUOTATION_ID'])->first();

            // dd($QUOTATION);
            // dd($PROSPECT_CUSTOMER);

            $PERSON = new PERSON([
                'APP_ID' => $data['APP_ID'],
                'PRODUCT_ID' => $data['PRODUCT_ID'],
                'CIF_PERSON_ID' => null,
                'JURISTIC_ID' => null,
                'FLAG_GUARANTOR' => $QUOTATION->FLAG_GUARANTOR,
                'PREFIX' => $PROSPECT_CUSTOMER->PREFIX,
                'PREFIX_ENG' => null,
                'PREFIX_OTHER' => null,
                'FIRST_NAME' => $PROSPECT_CUSTOMER->FIRST_NAME,
                'FIRST_NAME_ENG' => null,
                'LAST_NAME' => $PROSPECT_CUSTOMER->LAST_NAME,
                'LAST_NAME_ENG' => null,
                'CARD_CODE' => null,
                'TAX_ID' => $PROSPECT_CUSTOMER->TAX_ID,
                'STUDENT_ID' => $PROSPECT_CUSTOMER->STUDENT_ID,
                'BIRTHDAY' => $PROSPECT_CUSTOMER->BIRTHDAY,
                'AGE' => $data['AGE'],
                'CUSTOMER_TYPE' => null,
                'SEX' => $PROSPECT_CUSTOMER->SEX,
                'IDCARD_REGIS_DATE' => null,
                'IDCARD_EXPIRE_DATE' => null,
                'IDCARD_REGIS_BY' => null,
                'NATIONALITY_CODE' => $PROSPECT_CUSTOMER->NATIONALITY_CODE,
                'CITIZEN_CODE' => null,
                'MARITAL_STATUS' => $PROSPECT_CUSTOMER->MARITAL_STATUS,
                'NUMBER_CHILDREN' => null,
                'STUDY_CHILD' => null,
                'PHONE' => $PROSPECT_CUSTOMER->PHONE,
                'EMAIL' => $PROSPECT_CUSTOMER->EMAIL,
                'LINEID' => $PROSPECT_CUSTOMER->LINEID,
                'FACEBOOK' => $PROSPECT_CUSTOMER->FACEBOOK,
                'OCCUPATION_CODE' => $PROSPECT_CUSTOMER->OCCUPATION_CODE,
                'OCCUPATION_TYPE_CODE' => $PROSPECT_CUSTOMER->OCCUPATION_TYPE_CODE,
                'BUSINESSTYPE_CODE' => null,
                'OCCUPATION_CODE_OTHER' => null,
                'UNIVERSITY_PROVINCE' => $PROSPECT_CUSTOMER->UNIVERSITY_PROVINCE,
                'UNIVERSITY_DISTRICT' => $PROSPECT_CUSTOMER->UNIVERSITY_DISTRICT,
                'UNIVERSITY_NAME' => $PROSPECT_CUSTOMER->UNIVERSITY_NAME,
                'UNIVERSITY_OTHER' => $PROSPECT_CUSTOMER->UNIVERSITY_OTHER,
                'CAMPUS_NAME' => $PROSPECT_CUSTOMER->CAMPUS_NAME,
                'FACULTY_NAME' => $PROSPECT_CUSTOMER->FACULTY_ID,
                'FACULTY_OTHER' => $PROSPECT_CUSTOMER->FACULTY_OTHER,
                'SUBJECT' => $PROSPECT_CUSTOMER->SUBJECT_NAME,
                'LEVEL_TYPE' => $PROSPECT_CUSTOMER->LEVEL_TYPE,
                'U_LEVEL' => $PROSPECT_CUSTOMER->U_LEVEL,
                'LOAN_KYS' => $PROSPECT_CUSTOMER->LOAN_KYS,
                'OFFICE_NAME' => null,
                'BUSINESS_TYPE' => null,
                'OFFICE_POSITION' => null,
                'OFFICE_LEVEL' => null,
                'OFFICE_YEAR' => null,
                'OFFICE_MONTH' => null,
                'OFFICE_PHONE' => null,
                'OFFICE_PHONE_EXTEN' => null,
                'INCOME_TYPE' => null,
                'MAIN_INCOME' => $PROSPECT_CUSTOMER->MAIN_INCOME,
                'EXPENSE' => null,
                'OTHER_INCOME' => null,
                'AMOUNT_INCOME' => null,
                'SOURCE_INCOME' => null,
                'SOURCE_OTHER_INCOME' => null,
                'SPOUSE_TITLE' => null,
                'SPOUSE_FIRSTNAME' => null,
                'SPOUSE_LASTNAME' => null,
                'SPOUSE_OCCUPATION' => null,
                'SPOUSE_OFFICE_NAME' => null,
                'SPOUSE_BUSINESS_SIZE' => null,
                'SPOUSE_OFFICE_POSITION' => null,
                'SPOUSE_OFFICE_LEVEL' => null,
                'SPOUSE_OFFICE_YEAR' => null,
                'SPOUSE_MAIN_INCOME' => null,
                'SPOUSE_EXPENSE' => null,
                'SPOUSE_OTHER_INCOME' => null,
                'S_SOURCE_OTHER_INCOME' => null,
                'RELATION_REFERENCE' => $PROSPECT_CUSTOMER->RELATION_REFERENCE,
                'RELATION_REF_DES' => $PROSPECT_CUSTOMER->RELATION_REF_DES,
                'REF_TAX_ID' => $PROSPECT_CUSTOMER->REF_TAX_ID,
                'REF_TITLE' => $QUOTATION->FLAG_GUARANTOR == 1 ? 4 : $PROSPECT_CUSTOMER->REF_TITLE,
                'REF_TITLE_OTHER' => $PROSPECT_CUSTOMER->REF_TITLE_OTHER,
                'REF_FIRSTNAME' => $PROSPECT_CUSTOMER->REF_FIRSTNAME,
                'REF_LASTNAME' => $PROSPECT_CUSTOMER->REF_LASTNAME,
                'REF_BIRTHDAY' => $PROSPECT_CUSTOMER->REF_BIRTHDAY,
                'REF_AGE' => $PROSPECT_CUSTOMER->REF_AGE,
                'REF_OCCUPATION' => $PROSPECT_CUSTOMER->REF_OCCUPATION,
                'REF_OFFICE_NAME' => null,
                'REF_BUSINESS_SIZE' => null,
                'REF_OFFICE_POSITION' => null,
                'REF_OFFICE_LEVEL' => null,
                'REF_OFFICE_YEAR' => null,
                'REF_MAIN_INCOME' => null,
                'REF_EXPENSE' => null,
                'REF_OTHER_INCOME' => null,
                'REF_SOURCE_OTHER_INCOME' => null,
                'REF_PHONE' => null,
                'REF_EMAIL' => null,
                'REF_LINEID' => null,
                'REF_FACEBOOK' => null,
                'CARD_CODE_FILE' => $PROSPECT_CUSTOMER->IDCARD_FILE,
                'CARD_CODE_FILE_SECOND' => null,
                'STUDENT_CARD_FILE' => $PROSPECT_CUSTOMER->STUDENTCARD_FILE,
                'STUDENT_CARD_FILE_SECOND' => null,
                'FACE_PERSON' => $PROSPECT_CUSTOMER->FACE_FILE,
                'FACE_PERSON_SECOND' => null,
                'CONSENT_FILE' => null,
                'STATEMENT_FILE_THIRD' => null,
                'STATEMENT_FILE_SECOND' => null,
                'STATEMENT_FILE' => null,
                'BANK_STATE_FILE_FOURTH' => null,
                'BANK_STATE_FILE_THIRD' => null,
                'BANK_STATE_FILE_SECOND' => null,
                'BANK_STATE_FILE' => null,
                'SLIP_FILE' => null,
                'ADR_MAP' => null,
                'DS_FILE' => null,
                'DS_IMAGE' => null,
                'DS_PDPA' => null,
                'DS_MKR' => null,
                'DS_GUAR' => null,
                'CONFIRM_INFOR' => null,
                'CONFIRM_PDPA' => null,
                'CREATE_DATE' => $this->DateStr,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => 'API',
                'PHONE_SECOND' => null,
                'REF_PHONE_WORK' => null,
                'REF_PHONE_SECOND' => null,
                'Disease_ID' => null,
                'Narcotic_ID' => null,
                'APPROVE_CUSTOMER' => null,
                'FINANCIAL_AMOUNT' => null,
                'FINANCIAL_AMOUNT_TRUE' => null,
                'CREDIT_CARD' => null,
                'INSTITUTION_BANK' => null,
                'INSTITUTION_BANK_AMOUNT' => null,
                'TYPE_LOAN_HP' => null,
                'Acknowledge_ID' => null,
                'Balloon_Type' => null,
                'TRADE_IN_TYPE' => null,
                'RELATION_REFERENCE_2' => null,
                'RELATION_REF_DES_2' => null,
                'REF_TAX_ID_2' => null,
                'REF_TITLE_2' => null,
                'REF_TITLE_OTHER_2' => null,
                'REF_FIRSTNAME_2' => null,
                'REF_LASTNAME_2' => null,
                'REF_BIRTHDAY_2' => null,
                'REF_AGE_2' => null,
                'REF_OCCUPATION_2' => null,
                'REF_PHONE_2' => null,
                'REF_PHONE_SECCOND_2' => null,
            ]);

            $PERSON->save();

            ADDRESS::where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->update([
                    'PERSON_ID' =>  $PERSON->PERSON_ID,
                ]);

            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }
}
