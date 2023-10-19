<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;
use App\Http\Controllers\UFUND\Error_Exception;

use \Gumlet\ImageResize;


class API_PROSPECT_CUSTOMER extends BaseController
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
        // dd($image);
        // $img_resize = $image;
        // $image_catch = base64_encode(file_get_contents($image));

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

            // Get PROSPECT_CUSTOMER
            $GET_PROSPECT_CUSTOMER = DB::table('dbo.PROSPECT_CUSTOMER')
                ->select('*')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->where('TAX_ID', $data['TAX_ID'])
                ->orderBy('PST_CUST_ID', 'DESC')
                ->get();


            // get Guarantor
            $GET_FLAG_GUARANTOR = $this->GetGUARANTOR($data);

            // dd($GET_PROSPECT_CUSTOMER);
            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
            // Check Cerate Or Update
            $UpdateTime = null;
            if (isset($GET_PROSPECT_CUSTOMER[0]->CREATE_DATE)) {
                $UpdateTime = $date_now;
            }


            $BIRTHDAY_Carbon = Carbon::parse($data['BIRTHDAY']);
            if ($BIRTHDAY_Carbon->isPast() == false) {
                $BIRTHDAY_Carbon->add(-543, 'year');
            }
            $BIRTHDAY = $BIRTHDAY_Carbon->format('Y-m-d');

            $REF_BIRTHDAY_Carbon = Carbon::parse($data['REF_BIRTHDAY']);
            if ($REF_BIRTHDAY_Carbon->isPast() == false) {
                $REF_BIRTHDAY_Carbon->add(-543, 'year');
            }
            $REF_BIRTHDAY = $REF_BIRTHDAY_Carbon->format('Y-m-d');
            // dd($BIRTHDAY);

            // dd($data['IDCARD_FILE']->getClientOriginalExtension());
            $IDCARD_FILE = "<file><name>Img-IDCard-" . $data['TAX_ID'] . '.' . $data['IDCARD_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['IDCARD_FILE']) . "</content></file>";
            $STUDENTCARD_FILE = "<file><name>Img-StudentCard-" . $data['TAX_ID'] . '.' . $data['STUDENTCARD_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['STUDENTCARD_FILE']) . "</content></file>";
            $FACE_FILE = "<file><name>Img-Face-" . $data['TAX_ID'] . '.' . $data['FACE_FILE']->getClientOriginalExtension() . "</name><content>" . $this->ResizeImage($data['FACE_FILE']) . "</content></file>";

            // $IDCARD_FILE = "<file><name>Img-IDCard-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['IDCARD_FILE']))."</content></file>";
            // $STUDENTCARD_FILE = "<file><name>Img-StudentCard-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['STUDENTCARD_FILE']))."</content></file>";
            // $FACE_FILE = "<file><name>Img-Face-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['FACE_FILE']))."</content></file>";

            // $IDCARD_FILE = "";
            // $STUDENTCARD_FILE = "";
            // $FACE_FILE = "";

            // dd($data['IDCARD_FILE']);
            // dd($IDCARD_FILE);


            // Get Level_TYPE
            $GET_LEVEL_TYPE = DB::table('dbo.MT_LEVEL_TYPE')
                ->select('*')
                ->where('LEVEL_TYPE_ID', $data['LEVEL_TYPE'])
                ->first();


            // Get University Detail
            $MT_UNIVERSITY_NAME = DB::table('dbo.MT_UNIVERSITY_NAME')
                ->select('*')
                ->where('MT_UNIVERSITY_ID', $data['UNIVERSITY_ID'])
                ->first();

            DB::table('dbo.PROSPECT_CUSTOMER')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->where('TAX_ID', $data['TAX_ID'])
                ->update([
                    'PREFIX' => $data['PREFIX'],
                    'PREFIX_ENG' => null,
                    'PREFIX_OTHER' => isset($data['PREFIX_OTHER']) ? $data['PREFIX_OTHER'] : null,
                    'FIRST_NAME' => $data['FIRST_NAME'],
                    'FIRST_NAME_ENG' => null,
                    'LAST_NAME' => $data['LAST_NAME'],
                    'LAST_NAME_ENG' => null,
                    'TAX_ID' => $data['TAX_ID'],
                    'IDCARD_REGIS_DATE' => null,
                    'IDCARD_EXPIRE_DATE' => null,
                    'NATIONALITY_CODE' => null,
                    'STUDENT_ID' => isset($data['STUDENT_ID']) ? $data['STUDENT_ID'] : null,
                    'BIRTHDAY' => $BIRTHDAY,
                    'AGE' => isset($data['AGE']) ? $data['AGE'] : null,
                    'SEX' => $data['SEX'],
                    'MARITAL_STATUS' => $data['MARITAL_STATUS'],
                    'PHONE' => $data['PHONE'],
                    'PHONE_SECOND' => isset($data['PHONE_SECOND']) ? $data['PHONE_SECOND'] : null,
                    'EMAIL' => $data['EMAIL'],
                    'FACEBOOK' => isset($data['FACEBOOK']) ? $data['FACEBOOK'] : null,
                    'LINEID' => isset($data['LINEID']) ? $data['LINEID'] : null,
                    'OCCUPATION_CODE' => $data['OCCUPATION_ID'],
                    'OCCUPATION_TYPE_CODE' => null,
                    'BUSINESSTYPE_CODE' => null,
                    'MAIN_INCOME' => $data['MAIN_INCOME'],
                    'OTHER_INCOME' => null,
                    'AMOUNT_INCOME' => null,
                    // 'UNIVERSITY_PROVINCE' => $data['UNIVERSITY_PROVINCE'],
                    // 'UNIVERSITY_DISTRICT' => isset($data['UNIVERSITY_DISTRICT']) ? $data['UNIVERSITY_DISTRICT'] : null,
                    // 'UNIVERSITY_PROVINCE' => $MT_UNIVERSITY_NAME->PROVINCE_ID,
                    // 'UNIVERSITY_DISTRICT' => $MT_UNIVERSITY_NAME->DISTRICT_ID,
                    'UNIVERSITY_NAME' => $data['UNIVERSITY_ID'],
                    'UNIVERSITY_OTHER' => isset($data['UNIVERSITY_OTHER']) ? $data['UNIVERSITY_OTHER'] : null,
                    'CAMPUS_NAME' => isset($data['CAMPUS_NAME']) ? $data['CAMPUS_NAME'] : null,
                    'FACULTY_NAME' => $data['FACULTY_ID'],
                    'FACULTY_OTHER' => isset($data['FACULTY_OTHER']) ? $data['FACULTY_OTHER'] : null,
                    'SUBJECT_NAME' => isset($data['SUBJECT_NAME']) ? $data['SUBJECT_NAME'] : null,
                    'LEVEL_TYPE' => $GET_LEVEL_TYPE->LEVEL_TYPE,
                    'U_LEVEL' => $data['U_LEVEL'],
                    'LOAN_KYS' => isset($data['LOAN_KYS']) ? $data['LOAN_KYS'] : null,
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
                    'CREATE_DATE' => $GET_PROSPECT_CUSTOMER[0]->CREATE_DATE == null ? $date_now : $GET_PROSPECT_CUSTOMER[0]->CREATE_DATE,
                    'UPDATE_DATE' => null,
                    'NAME_MAKE' => "API",
                    'PHONE_SECOND' => isset($data['PHONE_SECOND']) ? $data['PHONE_SECOND'] : null,
                    'Disease_ID' => null,
                    'Narcotic_ID' => null,
                    'APPROVE_CUSTOMER' => null,
                    'CREDIT_CARD' => null,
                    'FINANCIAL_AMOUNT' => null,
                    'FINANCIAL_AMOUNT_TRUE' => null,
                    'INSTITUTION_BANK' => null,
                    'INSTITUTION_BANK_AMOUNT' => null,
                    'Acknowledge_ID' => null,
                    'Balloon_Type' => null,
                ]);


            if ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1) {
                // DB::table('dbo.PROSPECT_GUARANTOR')->insert([
                //     'QUOTATION_ID' => $data['QUOTATION_ID'],
                //     'PREFIX' =>  $data['REF_TITLE'],
                //     'FIRST_NAME' => $data['REF_FIRSTNAME'],
                //     'LAST_NAME' => $data['REF_LASTNAME'],
                //     'MOBILE' => $data['REF_PHONE'],
                //     'EMAIL' => $data['EMAILGuarantor'],
                //     'RESULT_GUARANTOR' => 'WAIT',
                // ]);
                DB::table('dbo.PROSPECT_GUARANTOR')
                    ->where('PST_GUAR_ID', $data['PST_GUAR_ID'])
                    ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                    ->update([
                        'PREFIX' =>  $data['REF_TITLE'],
                        'FIRST_NAME' => $data['REF_FIRSTNAME'],
                        'LAST_NAME' => $data['REF_LASTNAME'],
                        'MOBILE' => $data['REF_PHONE'],
                        'EMAIL' => $data['EMAILGuarantor'],
                        'RESULT_GUARANTOR' => 'WAIT',
                    ]);
                // DB::table('dbo.PROSPECT_CUSTOMER')
                //     ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                //     ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                //     ->update([
                //         'REF_TITLE' =>  $data['REF_TITLE'],
                //         'REF_FIRSTNAME' => $data['REF_FIRSTNAME'],
                //         'REF_LASTNAME' => $data['REF_LASTNAME'],
                //         'REF_PHONE' => $data['REF_PHONE'],
                //         // 'EMAIL' => $data['EMAILGuarantor'],
                //         // 'RESULT_GUARANTOR' => 'WAIT',
                //     ]);
            } else {

                $RELATION_REF_DES = DB::table('dbo.MT_RELATIONSHIP_REF')
                    ->select('*')
                    ->where('RELATION_REF_ID', $data['RELATION_REFERENCE'])
                    ->get();

                DB::table('dbo.PROSPECT_CUSTOMER')
                    ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                    ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                    ->where('TAX_ID', $data['TAX_ID'])
                    ->update([
                        'REF_TAX_ID' => $data['REF_TAX_ID'],
                        'REF_TITLE' => $data['REF_TITLE'],
                        'REF_TITLE_OTHER' => isset($data['REF_TITLE_OTHER']) ? $data['REF_TITLE_OTHER'] : null,
                        'REF_FIRSTNAME' => $data['REF_FIRSTNAME'],
                        'REF_LASTNAME' => $data['REF_LASTNAME'],
                        'RELATION_REFERENCE' => $data['RELATION_REFERENCE'],
                        'RELATION_REF_DES' => isset($RELATION_REF_DES[0]->RELATION_REF_NAME) ? $RELATION_REF_DES[0]->RELATION_REF_NAME : null,
                        'REF_OCCUPATION' => $data['REF_OCCUPATION'],
                        'REF_AGE' => isset($data['REF_AGE']) ? $data['REF_AGE'] : null,
                        'REF_BIRTHDAY' => $REF_BIRTHDAY,
                        'REF_PHONE' => $data['REF_PHONE'],
                        'REF_PHONE_SECOND' => isset($data['REF_PHONE_SECOND']) ? $data['REF_PHONE_SECOND'] : null,
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
            }


            return response()->json(array(
                'code' => '0000',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $data['TAX_ID'],
                    'QUOTATION_ID' => $data['QUOTATION_ID'],
                    'PST_CUST_ID' => $data['PST_CUST_ID']
                ]
            ));
        } catch (Exception $e) {
            return $this->Error_Exception->Msg_error($e);
        }
    }


    function validateInput($data)
    {
        $validate = [
            "PST_CUST_ID" => [
                'message' => 'Request Parameter [PST_CUST_ID]',
                'numeric' => true,
            ],
            "QUOTATION_ID" => [
                'message' => 'Request Parameter [QUOTATION_ID]',
                'numeric' => true,
            ],
            "PREFIX" => [
                'message' => 'Request Parameter [PREFIX]',
                'numeric' => true,
            ],
            "FIRST_NAME" => [
                'message' => 'Request Parameter [FIRST_NAME]',
                'numeric' => false,
            ],
            "LAST_NAME" => [
                'message' => 'Request Parameter [LAST_NAME]',
                'numeric' => false,
            ],
            "TAX_ID" => [
                'message' => 'Request Parameter [TAX_ID]',
                'numeric' => true,
                'tax_id' => true,
            ],
            "STUDENT_ID" => [
                'message' => 'Request Parameter [STUDENT_ID]',
                'numeric' => false,
            ],
            "BIRTHDAY" => [
                'message' => 'Request Parameter [BIRTHDAY]',
                'numeric' => false,
                'typeDate' => true,
            ],
            "SEX" => [
                'message' => 'Request Parameter [SEX]',
                'numeric' => true,
            ],
            "MARITAL_STATUS" => [
                'message' => 'Request Parameter [MARITAL_STATUS]',
                'numeric' => true,
            ],
            "PHONE" => [
                'message' => 'Request Parameter [PHONE]',
                'numeric' => false,
            ],
            "EMAIL" => [
                'message' => 'Request Parameter [EMAIL]',
                'numeric' => false,
            ],
            "OCCUPATION_ID" => [
                'message' => 'Request Parameter [OCCUPATION_ID]',
                'numeric' => true,
            ],
            "MAIN_INCOME" => [
                'message' => 'Request Parameter [MAIN_INCOME]',
                'numeric' => true,
            ],
            // "UNIVERSITY_PROVINCE" => [
            //     'message' => 'Request Parameter [UNIVERSITY_PROVINCE]',
            //     'numeric' => true,
            // ],
            // "UNIVERSITY_DISTRICT" => [
            //     'message' => 'Request Parameter [UNIVERSITY_DISTRICT]',
            //     'numeric' => true,
            // ],
            "UNIVERSITY_ID" => [
                'message' => 'Request Parameter [UNIVERSITY_ID]',
                'numeric' => true,
            ],
            "FACULTY_ID" => [
                'message' => 'Request Parameter [FACULTY_ID]',
                'numeric' => true,
            ],
            "LEVEL_TYPE" => [
                'message' => 'Request Parameter [LEVEL_TYPE]',
                'numeric' => true,
            ],
            "U_LEVEL" => [
                'message' => 'Request Parameter [U_LEVEL]',
                'numeric' => true,
            ],

            // File image
            "IDCARD_FILE" => [
                'message' => 'Request Parameter [IDCARD_FILE]',
                'numeric' => false,
                'file' => true,
            ],
            "STUDENTCARD_FILE" => [
                'message' => 'Request Parameter [STUDENTCARD_FILE]',

                'numeric' => false,
                'file' => true,
            ],
            "FACE_FILE" => [
                'message' => 'Request Parameter [FACE_FILE]',
                'numeric' => false,
                'file' => true,
            ],
            // "Guarantor" => [
            //     'message' => 'Request Parameter [Guarantor]',
            //     'numeric' => true,
            // ],
        ];


        foreach ($validate as $key => $value) {
            if (!isset($data[$key])) {
                throw new Exception($value['message'], 1000);
            }

            if ($value['numeric'] == true) {
                if (!is_numeric($data[$key])) {
                    throw new Exception('Request Type of $(int) [' . $key . '] ', 1000);
                }
            }

            if (isset($value['tax_id'])) {
                // var_dump(strlen($data[$key]));
                if (strlen($data[$key]) != 13) {
                    throw new Exception("TAX ID must have 13 digits.", 1000);
                }
            }

            if (isset($value['typeDate'])) {
                if (strtotime($data[$key]) == false) {
                    throw new Exception('Request Type of $(date) [' . $key . '] ', 1000);
                }
            }


            if (isset($value['file'])) {
                if (!is_file($data[$key])) {
                    throw new Exception('Request File [' . $key . '] ', 1000);
                } else if ($this->is_image($data[$key]) == false) {
                    throw new Exception('Request Image File [' . $key . '] ', 1000);
                }
            }
        }
    }


    function GetGUARANTOR($data)
    {
        $GET_FLAG_GUARANTOR = DB::table('dbo.QUOTATION')
            ->select('FLAG_GUARANTOR')
            ->where('QUOTATION_ID', $data['QUOTATION_ID'])
            ->where('TAX_ID', $data['TAX_ID'])
            ->get();



        // Guarantor
        $validateGuarantor = [
            "PST_GUAR_ID" => [
                'message' => 'Request Parameter [PST_GUAR_ID]',
                'numeric' => true,
                'Guarantor' => true,
                'RequestGuarantor' => true,
            ],
            "REF_TAX_ID" => [
                'message' => 'Request Parameter [REF_TAX_ID]',
                'numeric' => true,
                'tax_id' => true,
            ],
            "REF_TITLE" => [
                'message' => 'Request Parameter [REF_TITLE]',
                'numeric' => true,
                'Guarantor' => true,
            ],
            "REF_FIRSTNAME" => [
                'message' => 'Request Parameter [REF_FIRSTNAME]',
                'numeric' => false,
                'Guarantor' => true,
            ],
            "REF_LASTNAME" => [
                'message' => 'Request Parameter [REF_LASTNAME]',
                'numeric' => false,
                'Guarantor' => true,
            ],
            "RELATION_REFERENCE" => [
                'message' => 'Request Parameter [RELATION_REFERENCE]',
                'numeric' => true,
            ],
            // "RELATION_REF_DES" => [
            //     'message' => 'Request Parameter [RELATION_REF_DES]',
            //     'numeric' => false,
            // ],
            "REF_OCCUPATION" => [
                'message' => 'Request Parameter [REF_OCCUPATION]',
                'numeric' => true,
            ],
            "REF_BIRTHDAY" => [
                'message' => 'Request Parameter [REF_BIRTHDAY]',
                'numeric' => false,
                'typeDate' => true,
            ],
            "REF_PHONE" => [
                'message' => 'Request Parameter [REF_PHONE]',
                'numeric' => false,
                'Guarantor' => true,
            ],
            "EMAILGuarantor" => [
                'message' => 'Request Parameter [EMAILGuarantor]',
                'numeric' => false,
                'Guarantor' => true,
                'RequestGuarantor' => true,
            ],
        ];

        if ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1) {
            foreach ($validateGuarantor as $key => $value) {
                if (isset($value['Guarantor'])) {
                    if (!isset($data[$key])) {
                        throw new Exception($value['message'], 1000);
                    }

                    if (isset($value['tax_id'])) {
                        if (strlen($data[$key]) != 13) {
                            throw new Exception("TAX ID must have 13 digits.", 1000);
                        }
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . '] ', 1000);
                        }
                    }
                }
            }
        } else {
            foreach ($validateGuarantor as $key => $value) {
                if (!isset($value['RequestGuarantor'])) {
                    if (!isset($data[$key])) {
                        throw new Exception($value['message'], 1000);
                    }

                    if (isset($value['tax_id'])) {
                        if (strlen($data[$key]) != 13) {
                            throw new Exception("TAX ID must have 13 digits.", 1000);
                        }
                    }

                    if ($value['numeric'] == true) {
                        if (!is_numeric($data[$key])) {
                            throw new Exception('Request Type of $(int) [' . $key . '] ', 1000);
                        }
                    }

                    if (isset($value['typeDate'])) {
                        if (strtotime($data[$key]) == false) {
                            throw new Exception('Request Type of $(date) [' . $key . '] ', 1000);
                        }
                    }
                }
            }
        }

        return $GET_FLAG_GUARANTOR;
    }
}
