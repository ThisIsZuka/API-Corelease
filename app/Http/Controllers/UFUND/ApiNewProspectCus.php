<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UFUND\Error_Exception;
use App\Models\PROSPECT_CUSTOMER;
use App\Models\MT_LEVEL_TYPE;
use App\Models\MT_UNIVERSITY_NAME;
use App\Models\MT_RELATIONSHIP_REF;
use App\Models\PROSPECT_GUARANTOR;
use App\Models\QUOTATION;

use \Gumlet\ImageResize;


class ApiNewProspectCus extends BaseController
{

    private $Error_Exception;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
    }

    private function is_image($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];

        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            return true;
        }
        return false;
    }



    public function ResizeImage($image)
    {
        try {
            if (filesize($image) > 2000000) {

                $img_resize = filesize($image);
                $new_img_resize = $img_resize;
                $num_count = 0;
                while ($new_img_resize > 2000000) {
                    if ($new_img_resize > 1000000) {
                        $num_count = 0.8;
                        break;
                    }
                    $num_count += 0.1;
                    $new_img_resize = $img_resize;
                    // var_dump($new_img_resize . " = " . $new_img_resize . " - " . " ( " . filesize($image) * $num_count . ")");
                    // echo "<br>";
                    $new_img_resize = $new_img_resize - (filesize($image)  * $num_count);
                    // var_dump($num_count);

                    if ($num_count > 0.70) {
                        $num_count = 0.70;
                        break;
                    }
                }
                $num_count = $num_count * 100;

                $image_resize = base64_encode(file_get_contents($image));

                $num_count =  100 - $num_count;

                $image_resize = ImageResize::createFromString(base64_decode($image_resize));
                $image_resize->scale($num_count);
                $image_resize = base64_encode($image_resize);
            } else {
                $image_resize = base64_encode(file_get_contents($image));
            }
            return $image_resize;
        } catch (Exception $e) {

            $image_b64 = base64_encode(file_get_contents($image));
            return $image_b64;
        }
    }



    public function NEW_PROSPECT_CUSTOMER(Request $request)
    {
        try {

            $data = $request->all();
            $this->validateInput($data);

            $QUOTATION_ID = $data['QUOTATION_ID'];
            $PST_CUST_ID = $data['PST_CUST_ID'];
            $TAX_ID = $data['TAX_ID'];
            $BIRTHDAY = $data['BIRTHDAY'];
            // $IDCARD_FILE = $data['IDCARD_FILE'];
            // $STUDENTCARD_FILE = $data['STUDENTCARD_FILE'];
            // $FACE_FILE = $data['FACE_FILE'];
            $PREFIX = $data['PREFIX'];
            $PREFIX_OTHER = $data['PREFIX_OTHER'] ?? null;
            $FIRST_NAME = $data['FIRST_NAME'];
            $LAST_NAME = $data['LAST_NAME'];
            $STUDENT_ID = $data['STUDENT_ID'] ?? null;
            $AGE = $data['AGE'] ?? null;
            $SEX = $data['SEX'];
            $MARITAL_STATUS = $data['MARITAL_STATUS'];
            $PHONE = $data['PHONE'];
            $PHONE_SECOND = $data['PHONE_SECOND'] ?? null;
            $EMAIL = $data['EMAIL'];
            $FACEBOOK = $data['FACEBOOK'] ?? null;
            $LINEID = $data['LINEID'] ?? null;
            $OCCUPATION_ID = $data['OCCUPATION_ID'];
            $MAIN_INCOME = $data['MAIN_INCOME'] ?? null;
            $UNIVERSITY_ID = $data['UNIVERSITY_ID'];
            $UNIVERSITY_OTHER = $data['UNIVERSITY_OTHER'] ?? null;
            $CAMPUS_NAME = $data['CAMPUS_NAME'] ?? null;
            $FACULTY_ID = $data['FACULTY_ID'];
            $FACULTY_OTHER = $data['FACULTY_OTHER'] ?? null;
            $SUBJECT_NAME = $data['SUBJECT_NAME'] ?? null;
            $U_LEVEL = $data['U_LEVEL'];
            $LOAN_KYS = $data['LOAN_KYS'] ?? null;
            $LEVEL_TYPE = $data['LEVEL_TYPE'];
            $NATIONALITY_CODE = $data['NATIONALITY_CODE'];
            $Narcotic_ID = $data['Narcotic_ID'];
            $Disease_ID = $data['Disease_ID'];

            $REF_TAX_ID = $data['REF_TAX_ID'] ?? null;
            $REF_TITLE = $data['REF_TITLE'] ?? null;
            $REF_TITLE_OTHER = $data['REF_TITLE_OTHER'] ?? null;
            $REF_FIRSTNAME = $data['REF_FIRSTNAME'] ?? null;
            $REF_LASTNAME = $data['REF_LASTNAME'] ?? null;
            $REF_PHONE = $data['REF_PHONE'] ?? null;
            $REF_PHONE_SECOND = $data['REF_PHONE_SECOND'] ?? null;
            $EMAILGuarantor = $data['EMAILGuarantor'] ?? null;
            $RELATION_REFERENCE = $data['RELATION_REFERENCE'] ?? null;
            $REF_OCCUPATION = $data['REF_OCCUPATION'] ?? null;
            $REF_AGE = $data['REF_AGE'] ?? null;
            $REF_BIRTHDAY = $data['REF_BIRTHDAY'] ?? null;
            $PST_GUAR_ID = $data['PST_GUAR_ID'] ?? null;

            // แปลง พศ เป็น คศ
            $BIRTHDAY_Carbon = Carbon::parse($BIRTHDAY);
            if ($BIRTHDAY_Carbon->isPast() == false) {
                $BIRTHDAY_Carbon->add(-543, 'year');
            }
            $BIRTHDAY = $BIRTHDAY_Carbon->format('Y-m-d');

            // แปลง พศ เป็น คศ
            $REF_BIRTHDAY_Carbon = Carbon::parse($data['REF_BIRTHDAY']);
            if ($REF_BIRTHDAY_Carbon->isPast() == false) {
                $REF_BIRTHDAY_Carbon->add(-543, 'year');
            }
            $REF_BIRTHDAY = $REF_BIRTHDAY_Carbon->format('Y-m-d');

            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');

            // Get PROSPECT_CUSTOMER
            $PROSPECT_CUSTOMER = PROSPECT_CUSTOMER::where('PST_CUST_ID', $PST_CUST_ID)
                ->where('QUOTATION_ID', $QUOTATION_ID)
                ->where('TAX_ID', $TAX_ID)
                ->orderBy('PST_CUST_ID', 'DESC')
                ->first();

            // get Guarantor
            $GET_FLAG_GUARANTOR = $this->GetGUARANTOR($data);


            // dd($data['IDCARD_FILE']->getClientOriginalExtension());
            $IDCARD_FILE = "<file><name>Img-IDCard-" . $TAX_ID . '.' . $data['IDCARD_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['IDCARD_FILE']) . "</content></file>";
            $STUDENTCARD_FILE = "<file><name>Img-StudentCard-" . $TAX_ID . '.' . $data['STUDENTCARD_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['STUDENTCARD_FILE']) . "</content></file>";
            $FACE_FILE = "<file><name>Img-Face-" . $TAX_ID . '.' . $data['FACE_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['FACE_FILE']) . "</content></file>";


            // Get Level_TYPE
            $MT_LEVEL_TYPE = MT_LEVEL_TYPE::where('LEVEL_TYPE_ID', $LEVEL_TYPE)
                ->first();


            // Get University Detail
            $MT_UNIVERSITY_NAME = MT_UNIVERSITY_NAME::where('MT_UNIVERSITY_ID', $UNIVERSITY_ID)
                ->first();
            // dd($MT_UNIVERSITY_NAME);

            // Update the model's attributes
            $PROSPECT_CUSTOMER->fill([
                'PREFIX' => $PREFIX,
                'PREFIX_ENG' => null,
                'PREFIX_OTHER' => $PREFIX_OTHER,
                'FIRST_NAME' => $FIRST_NAME,
                'FIRST_NAME_ENG' => null,
                'LAST_NAME' => $LAST_NAME,
                'LAST_NAME_ENG' => null,
                'TAX_ID' => $TAX_ID,
                'IDCARD_REGIS_DATE' => null,
                'IDCARD_EXPIRE_DATE' => null,
                'NATIONALITY_CODE' => $NATIONALITY_CODE,
                'STUDENT_ID' => $STUDENT_ID,
                'BIRTHDAY' => $BIRTHDAY,
                'AGE' => $AGE,
                'SEX' => $SEX,
                'MARITAL_STATUS' => $MARITAL_STATUS,
                'PHONE' => $PHONE,
                'PHONE_SECOND' => $PHONE_SECOND,
                'EMAIL' => $EMAIL,
                'FACEBOOK' => $FACEBOOK,
                'LINEID' => $LINEID,
                'OCCUPATION_CODE' => $OCCUPATION_ID,
                'OCCUPATION_TYPE_CODE' => null,
                'BUSINESSTYPE_CODE' => null,
                'MAIN_INCOME' => $MAIN_INCOME,
                'OTHER_INCOME' => null,
                'AMOUNT_INCOME' => null,
                'UNIVERSITY_PROVINCE' => $MT_UNIVERSITY_NAME->PROVINCE_ID,
                'UNIVERSITY_DISTRICT' => $MT_UNIVERSITY_NAME->DISTRICT_ID,
                'UNIVERSITY_NAME' => $UNIVERSITY_ID,
                'UNIVERSITY_OTHER' => $UNIVERSITY_OTHER,
                'CAMPUS_NAME' => $CAMPUS_NAME,
                'FACULTY_NAME' => $FACULTY_ID,
                'FACULTY_OTHER' => $FACULTY_OTHER,
                'SUBJECT_NAME' => $SUBJECT_NAME,
                'LEVEL_TYPE' => $MT_LEVEL_TYPE->LEVEL_TYPE,
                'U_LEVEL' => $U_LEVEL,
                'LOAN_KYS' => $LOAN_KYS,
                'OFFICE_NAME' => null,
                'OFFICE_YEAR' => null,
                'OFFICE_MONTH' => null,
                // 'REF_TAX_ID' => $data['REF_TAX_ID'],
                // 'REF_TITLE' => $data['REF_TITLE'],
                // 'REF_TITLE_OTHER' => isset($data['REF_TITLE_OTHER']) ? $data['REF_TITLE_OTHER'] : null,
                // 'REF_FIRSTNAME' => $data['REF_FIRSTNAME'],
                // 'REF_LASTNAME' => $data['REF_LASTNAME'],
                // 'RELATION_REFERENCE' => $data['RELATION_REFERENCE'],
                // 'RELATION_REF_DES' => $data['RELATION_REF_DES'],
                // 'REF_OCCUPATION' => $data['REF_OCCUPATION'],
                // 'REF_AGE' => isset($data['REF_AGE']) ? $data['REF_AGE'] : null,
                // 'REF_BIRTHDAY' => $REF_BIRTHDAY,
                // 'REF_PHONE' => $data['REF_PHONE'],
                'IDCARD_FILE' => $IDCARD_FILE,
                'IDCARD_FILE_SECOND' => null,
                'STUDENTCARD_FILE' => $STUDENTCARD_FILE,
                'BANK_STATE_FILE' => null,
                'BANK_STATE_FILE_SECOND' => null,
                'BANK_STATE_FILE_THIRD' => null,
                'BANK_STATE_FILE_FOURTH' => null,
                'FACE_FILE' => $FACE_FILE,
                'FACE_FILE_SECOND' => null,
                'STATEMENT_FILE_THIRD' => null,
                'STATEMENT_FILE_SECOND' => null,
                'STATEMENT_FILE' => null,
                'URLMAP' => $data['URLMAP'],
                'CREATE_DATE' => $PROSPECT_CUSTOMER->CREATE_DATE == null ? $date_now : $PROSPECT_CUSTOMER->CREATE_DATE,
                'UPDATE_DATE' => null,
                'NAME_MAKE' => "API",
                'PHONE_SECOND' => $PHONE_SECOND,
                'Disease_ID' =>  $Disease_ID,
                'Narcotic_ID' => $Narcotic_ID,
                'APPROVE_CUSTOMER' => null,
                'CREDIT_CARD' => null,
                'FINANCIAL_AMOUNT' => null,
                'FINANCIAL_AMOUNT_TRUE' => null,
                'INSTITUTION_BANK' => null,
                'INSTITUTION_BANK_AMOUNT' => null,
                'Acknowledge_ID' => null,
                'Balloon_Type' => null,
            ]);

            // Save the changes
            $PROSPECT_CUSTOMER->save();

            if ($GET_FLAG_GUARANTOR->FLAG_GUARANTOR == 1) {
                PROSPECT_GUARANTOR::where('PST_GUAR_ID', $PST_GUAR_ID)
                    ->where('QUOTATION_ID', $QUOTATION_ID)
                    ->update([
                        'PREFIX' =>  $REF_TITLE,
                        'FIRST_NAME' => $REF_FIRSTNAME,
                        'LAST_NAME' => $REF_LASTNAME,
                        'MOBILE' => $REF_PHONE,
                        'EMAIL' => $EMAILGuarantor,
                        'RESULT_GUARANTOR' => 'WAIT',
                    ]);
            } else {

                $MT_RELATIONSHIP_REF = MT_RELATIONSHIP_REF::where('RELATION_REF_ID', $RELATION_REFERENCE)
                    ->first();
                // MT_RELATIONSHIP_REF

                $PROSPECT_CUSTOMER->fill([
                    'REF_TAX_ID' => $REF_TAX_ID,
                    'REF_TITLE' => $REF_TITLE,
                    'REF_TITLE_OTHER' => $REF_TITLE_OTHER,
                    'REF_FIRSTNAME' => $REF_FIRSTNAME,
                    'REF_LASTNAME' => $REF_LASTNAME,
                    'RELATION_REFERENCE' => $RELATION_REFERENCE,
                    'RELATION_REF_DES' => isset($MT_RELATIONSHIP_REF->RELATION_REF_NAME) ? $MT_RELATIONSHIP_REF->RELATION_REF_NAME : null,
                    'REF_OCCUPATION' => $REF_OCCUPATION,
                    'REF_AGE' => $REF_AGE,
                    'REF_BIRTHDAY' => $REF_BIRTHDAY,
                    'REF_PHONE' => $REF_PHONE,
                    'REF_PHONE_SECOND' => $REF_PHONE_SECOND,
                    'REF_TAX_ID_2' => null,
                    'REF_TITLE_2' => null,
                    'REF_TITLE_OTHER_2' => null,
                    'RELATION_REFERENCE_2' => null,
                    'RELATION_REF_DES_2' => null,
                    'REF_OCCUPATION_2' => null,
                    'REF_AGE_2' => null,
                    'REF_PHONE_2' => null,
                    'REF_BIRTHDAY_2' => null,
                    'REF_PHONE_SECOND_2' => null,
                    'REF_FIRSTNAME_2' => null,
                    'REF_LASTNAME_2' => null,
                ]);

                $PROSPECT_CUSTOMER->save();
            }


            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $TAX_ID,
                    'QUOTATION_ID' => $QUOTATION_ID,
                    'PST_CUST_ID' => $PST_CUST_ID
                ]
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }


    function validateInput($data)
    {
        $validationRules = [
            'PST_CUST_ID' => 'required|integer',
            'QUOTATION_ID' => 'required|integer',
            'PREFIX' => 'required|integer',
            'FIRST_NAME' => 'required',
            'LAST_NAME' => 'required',
            'TAX_ID' => 'required|numeric|digits:13',
            'STUDENT_ID' => 'required|integer',
            'BIRTHDAY' => 'required|date',
            'SEX' => 'required|integer',
            'MARITAL_STATUS' => 'required|integer',
            'PHONE' => 'required',
            'EMAIL' => 'required|email',
            'OCCUPATION_ID' => 'required|integer',
            'MAIN_INCOME' => 'required',
            'UNIVERSITY_ID' => 'required|integer',
            'FACULTY_ID' => 'required|integer',
            'LEVEL_TYPE' => 'required|integer',
            'U_LEVEL' => 'required|integer',
            'IDCARD_FILE' => 'required|file',
            'STUDENTCARD_FILE' => 'required|file',
            'FACE_FILE' => 'required|file',
            'NATIONALITY_CODE' => 'required',
            'Narcotic_ID' => 'required|numeric',
            'Disease_ID' => 'required|numeric',
        ];

        $messages = []; //custom message error. (this line for use defualt)

        $attributeNames = [
            'PST_CUST_ID' => 'PST_CUST_ID',
            'QUOTATION_ID' => 'QUOTATION_ID',
            'PREFIX' => 'PREFIX',
            'FIRST_NAME' => 'FIRST_NAME',
            'LAST_NAME' => 'LAST_NAME',
            'TAX_ID' => 'TAX_ID',
            'STUDENT_ID' => 'STUDENT_ID',
            'BIRTHDAY' => 'BIRTHDAY',
            'SEX' => 'SEX',
            'MARITAL_STATUS' => 'MARITAL_STATUS',
            'PHONE' => 'PHONE',
            'EMAIL' => 'EMAIL',
            'OCCUPATION_ID' => 'OCCUPATION_ID',
            'MAIN_INCOME' => 'MAIN_INCOME',
            'UNIVERSITY_ID' => 'UNIVERSITY_ID',
            'FACULTY_ID' => 'FACULTY_ID',
            'LEVEL_TYPE' => 'LEVEL_TYPE',
            'U_LEVEL' => 'U_LEVEL',
            'IDCARD_FILE' => 'IDCARD_FILE',
            'STUDENTCARD_FILE' => 'STUDENTCARD_FILE',
            'FACE_FILE' => 'FACE_FILE',
            'NATIONALITY_CODE' => 'NATIONALITY_CODE',
            'Narcotic_ID' => 'Narcotic_ID',
            'Disease_ID' => 'Disease_ID',
        ];

        $validator = Validator::make($data, $validationRules, $messages, $attributeNames);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first(), 1000);
        }
    }


    function GetGUARANTOR($data)
    {
        $QUOTATION = QUOTATION::where('QUOTATION_ID', $data['QUOTATION_ID'])
            ->where('TAX_ID', $data['TAX_ID'])
            ->first();

        $validationRules = [
            'PST_GUAR_ID' => 'nullable|numeric',
            'REF_TAX_ID' => 'required|numeric|digits:13',
            'REF_TITLE' => 'required|numeric',
            'REF_FIRSTNAME' => 'required|string',
            'REF_LASTNAME' => 'required|string',
            'RELATION_REFERENCE' => 'required|numeric',
            'REF_OCCUPATION' => 'required|numeric',
            'REF_BIRTHDAY' => 'required|date',
            'REF_PHONE' => 'required|numeric',
            'EMAILGuarantor' => 'nullable|email',
        ];

        $messages = []; //custom message error. (this line for use defualt)

        $attributeNames = [
            'PST_GUAR_ID' => 'PST_GUAR_ID',
            'REF_TAX_ID' => 'REF_TAX_ID',
            'REF_TITLE' => 'REF_TITLE',
            'REF_FIRSTNAME' => 'REF_FIRSTNAME',
            'REF_LASTNAME' => 'REF_LASTNAME',
            'RELATION_REFERENCE' => 'RELATION_REFERENCE',
            'REF_OCCUPATION' => 'REF_OCCUPATION',
            'REF_BIRTHDAY' => 'REF_BIRTHDAY',
            'REF_PHONE' => 'REF_PHONE',
            'EMAILGuarantor' => 'EMAILGuarantor',
        ];

        $validator = Validator::make($data, $validationRules, $messages, $attributeNames);

        // Apply conditional validation based on FLAG_GUARANTOR
        if ($QUOTATION->FLAG_GUARANTOR == 1) {
            $validator->sometimes('PST_GUAR_ID', 'required|numeric', function ($input) {
                return true;
            });
            $validator->sometimes('EMAILGuarantor', 'required|email', function ($input) {
                return true;
            });
        }


        if ($validator->fails()) {
            throw new Exception($validator->errors()->first(), 1000);
        }

        return $QUOTATION;
    }
}
