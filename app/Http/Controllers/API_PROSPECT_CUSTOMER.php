<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;

use \Gumlet\ImageResize;


class API_PROSPECT_CUSTOMER extends BaseController
{

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

        // $img_resize = $image;
        if (filesize($image) > 2000000) {

            $img_resize = filesize($image);
            $new_img_resize = $img_resize;
            $num_count = 0;
            while ($new_img_resize > 2000000) {
                if ($new_img_resize > 10000000) {
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
        // dd($image_resize);
    }



    public function NEW_PROSPECT_CUSTOMER(Request $request)
    {
        try {

            $data = $request->all();

            $validate = [
                "PST_CUST_ID" => [
                    'message' => 'Request Parameter [PST_CUST_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [PST_CUST_ID]',
                    //     'EN' => 'Request Parameter [PST_CUST_ID]'
                    // ],
                    'numeric' => true,
                ],
                "QUOTATION_ID" => [
                    'message' => 'Request Parameter [QUOTATION_ID]',
                    // 'message' => [
                    //     'TH' => 'Request Parameter [QUOTATION_ID]',
                    //     'EN' => 'Request Parameter [QUOTATION_ID]'
                    // ],
                    'numeric' => true,
                ],
                "PREFIX" => [
                    'message' => 'Request Parameter [PREFIX]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุคำนำหน้า',
                    //     'EN' => 'Please identify prefix'
                    // ],
                    'numeric' => true,
                ],
                "FIRST_NAME" => [
                    'message' => 'Request Parameter [FIRST_NAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุชื่อ',
                    //     'EN' => 'Please identify first name'
                    // ],
                    'numeric' => false,
                ],
                "LAST_NAME" => [
                    'message' => 'Request Parameter [LAST_NAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุนามสกุล',
                    //     'EN' => 'Please identify last name'
                    // ],
                    'numeric' => false,
                ],
                "TAX_ID" => [
                    'message' => 'Request Parameter [TAX_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขบัตรประชาชน',
                    //     'EN' => 'Please identify tax ID'
                    // ],
                    'numeric' => true,
                    'tax_id' => true,
                ],
                "STUDENT_ID" => [
                    'message' => 'Request Parameter [STUDENT_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุรหัสนักศึกษา',
                    //     'EN' => 'Please identify student ID'
                    // ],
                    'numeric' => false,
                ],
                "BIRTHDAY" => [
                    'message' => 'Request Parameter [BIRTHDAY]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุวันเกิด',
                    //     'EN' => 'Please identify birthday'
                    // ],
                    'numeric' => false,
                    'typeDate' => true,
                ],
                // "AGE" => [
                //     'message' => 'Request Parameter [AGE]',
                //     'numeric' => true,
                // ],
                "SEX" => [
                    'message' => 'Request Parameter [SEX]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเพศ',
                    //     'EN' => 'Please identify gender'
                    // ],
                    'numeric' => true,
                ],
                "MARITAL_STATUS" => [
                    'message' => 'Request Parameter [MARITAL_STATUS]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุสถานะ',
                    //     'EN' => 'Please identify marital status'
                    // ],
                    'numeric' => true,
                ],
                "PHONE" => [
                    'message' => 'Request Parameter [PHONE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเบอร์โทรศัทพ์',
                    //     'EN' => 'Please identify phone number'
                    // ],
                    'numeric' => false,
                ],
                "EMAIL" => [
                    'message' => 'Request Parameter [EMAIL]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุอีเมล',
                    //     'EN' => 'Please identify E-mail'
                    // ],
                    'numeric' => false,
                ],
                "OCCUPATION_CODE" => [
                    'message' => 'Request Parameter [OCCUPATION_CODE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุอาชีพ',
                    //     'EN' => 'Please identify occupation'
                    // ],
                    'numeric' => true,
                ],
                "MAIN_INCOME" => [
                    'message' => 'Request Parameter [MAIN_INCOME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุรายได้',
                    //     'EN' => 'Please identify main income'
                    // ],
                    'numeric' => true,
                ],
                "UNIVERSITY_PROVINCE" => [
                    'message' => 'Request Parameter [UNIVERSITY_PROVINCE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุจังหวัดที่ตั้งของมหาวิทยาลัย',
                    //     'EN' => 'Please identify provice of university'
                    // ],
                    'numeric' => true,
                ],
                // "UNIVERSITY_DISTRICT" => [
                //     'message' => 'Request Parameter [UNIVERSITY_DISTRICT]',
                //     'numeric' => true,
                // ],
                "UNIVERSITY_NAME" => [
                    'message' => 'Request Parameter [UNIVERSITY_NAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุมหาวิทยาลัย',
                    //     'EN' => 'Please identify university'
                    // ],
                    'numeric' => true,
                ],
                "FACULTY_NAME" => [
                    'message' => 'Request Parameter [FACULTY_NAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุคณะ',
                    //     'EN' => 'Please identify faculty'
                    // ],
                    'numeric' => true,
                ],
                "LEVEL_TYPE" => [
                    'message' => 'Request Parameter [LEVEL_TYPE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุระดับการศึกษา',
                    //     'EN' => 'Please identify education level'
                    // ],
                    'numeric' => false,
                ],
                "U_LEVEL" => [
                    'message' => 'Request Parameter [U_LEVEL]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุชั้นปี',
                    //     'EN' => 'Please identify university level'
                    // ],
                    'numeric' => true,
                ],
                // File image
                "IDCARD_FILE" => [
                    'message' => 'Request Parameter [IDCARD_FILE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาเพิ่มรูปถายบัตรประชาชน',
                    //     'EN' => 'Please attach of your ID card'
                    // ],
                    'numeric' => false,
                    'file' => true,
                ],
                "STUDENTCARD_FILE" => [
                    'message' => 'Request Parameter [STUDENTCARD_FILE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาเพิ่มรูปถ่ายบัตรนักศึกษา',
                    //     'EN' => 'Please attach of your student card'
                    // ],
                    'numeric' => false,
                    'file' => true,
                ],
                "FACE_FILE" => [
                    'message' => 'Request Parameter [FACE_FILE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาเพิ่มรูปถ่ายหน้าตรง',
                    //     'EN' => 'Please attach of your photo'
                    // ],
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
                    // throw new Exception($value['message']);
                    throw new Exception($value['message']);
                }

                if ($value['numeric'] == true) {
                    if (!is_numeric($data[$key])) {
                        throw new Exception('Request Type of $(int) [' . $key . '] ');
                        // $mes_error = new stdClass;
                        // foreach ($value['message'] as $key => $value) {
                        //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    }
                }

                if (isset($value['tax_id'])) {
                    // var_dump(strlen($data[$key]));
                    if (strlen($data[$key]) != 13) {
                        throw new Exception("TAX ID must have 13 digits.");
                        // $mes_error = (object)[
                        //     'TH' => 'หมายเลขบัตรประชาชนต้องมี 13 หลัก',
                        //     'EN' => 'TAX ID must have 13 digits'
                        // ];
                        // throw new Exception(json_encode($mes_error));
                    }
                }

                if (isset($value['typeDate'])) {
                    // $time = strtotime($data[$key]);
                    // $newformat = date('Y-m-d',$time);
                    // var_dump($newformat);
                    if (strtotime($data[$key]) == false) {
                        throw new Exception('Request Type of $(date) [' . $key . '] ');
                        // $mes_error = new stdClass;
                        // foreach ($value['message'] as $key => $value) {
                        //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    }
                }


                if (isset($value['file'])) {
                    // dd($this->is_image($data[$key]));
                    if (!is_file($data[$key])) {
                        throw new Exception('Request File [' . $key . '] ');
                        // $mes_error = new stdClass;
                        // foreach ($value['message'] as $key => $value) {
                        //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    } else if ($this->is_image($data[$key]) == false) {
                        throw new Exception('Request Image File [' . $key . '] ');
                        // $mes_error = new stdClass;
                        // foreach ($value['message'] as $key => $value) {
                        //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                        //     $mes_error->$key = $txt;
                        // }
                        // throw new Exception(json_encode($mes_error));
                    }
                }
            }



            // Get PROSPECT_CUSTOMER
            $GET_PROSPECT_CUSTOMER = DB::table('dbo.PROSPECT_CUSTOMER')
                ->select('*')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->where('TAX_ID', $data['TAX_ID'])
                ->orderBy('PST_CUST_ID', 'DESC')
                ->get();

            if (count($GET_PROSPECT_CUSTOMER) == 0) {
                throw new Exception('Not found Data. Check Parameter [\'PST_CUST_ID\'] , [\'QUOTATION_ID\'] , [\'TAX_ID\']');
                // $mes_error = (object)[
                //     'TH' => 'ไม่พบข้อมูลของท่าน',
                //     'EN' => 'Not found your Information'
                // ];
                // throw new Exception(json_encode($mes_error));
            }



            // Check Request Guarantor
            $GET_FLAG_GUARANTOR = DB::table('dbo.QUOTATION')
                ->select('FLAG_GUARANTOR')
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->where('TAX_ID', $data['TAX_ID'])
                ->get();
            // if( $GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 && (int)$data['Guarantor'] != 1 ){
            //     throw new Exception("QUOTATION_ID [". $data['QUOTATION_ID']."] is Request Guarantor");
            // }elseif( $GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 0 && (int)$data['Guarantor'] == 1 ){
            //     throw new Exception("QUOTATION_ID [". $data['QUOTATION_ID']."] is not Request Guarantor. Request person reference");
            // }

            // dd($GET_FLAG_GUARANTOR);


            // Guarantor
            $validateGuarantor = [
                "PST_GUAR_ID" => [
                    'message' => 'Request Parameter [PST_GUAR_ID]',
                    'numeric' => true,
                    'Guarantor' => true,
                    'NotGuarantor' => true,
                ],
                "REF_TAX_ID" => [
                    'message' => 'Request Parameter [REF_TAX_ID]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเลขบัตรประชาชนบุคคลอ้างอิง',
                    //     'EN' => 'Please identify reference of id card'
                    // ],
                    'numeric' => true,
                    'tax_id' => true,
                ],
                "REF_TITLE" => [
                    'message' => 'Request Parameter [REF_TITLE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุคำนำหน้า' . ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'ผู้ค้ำประกัน' : 'บุคคลอ้างอิง'),
                    //     'EN' => 'Please identify ' . ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'reference' : 'guarantor'). ' of prefix'
                    // ],
                    'numeric' => true,
                    'Guarantor' => true,
                ],
                "REF_FIRSTNAME" => [
                    'message' => 'Request Parameter [REF_FIRSTNAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุชื่อ' . ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'ผู้ค้ำประกัน' : 'บุคคลอ้างอิง'),
                    //     'EN' => 'Please identify '. ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'reference' : 'guarantor'). ' of first name'
                    // ],
                    'numeric' => false,
                    'Guarantor' => true,
                ],
                "REF_LASTNAME" => [
                    'message' => 'Request Parameter [REF_LASTNAME]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุนามสกุล' . ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'ผู้ค้ำประกัน' : 'บุคคลอ้างอิง'),
                    //     'EN' => 'Please identify '. ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'reference' : 'guarantor'). ' of last name'
                    // ],
                    'numeric' => false,
                    'Guarantor' => true,
                ],
                "RELATION_REFERENCE" => [
                    'message' => 'Request Parameter [RELATION_REFERENCE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุความสัมพันธ์กับบุลคลอ้างอิง',
                    //     'EN' => 'Please identify relationship of reference'
                    // ],
                    'numeric' => true,
                ],
                // "RELATION_REF_DES" => [
                //     'message' => 'Request Parameter [RELATION_REF_DES]',
                //     'numeric' => false,
                // ],
                "REF_OCCUPATION" => [
                    'message' => 'Request Parameter [REF_OCCUPATION]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุอาชีพบุลคลอ้างอิง',
                    //     'EN' => 'Please identify reference of occupation'
                    // ],
                    'numeric' => true,
                ],
                "REF_BIRTHDAY" => [
                    'message' => 'Request Parameter [REF_BIRTHDAY]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุวันเกิดบุลคลอ้างอิง',
                    //     'EN' => 'Please identify reference of birthday'
                    // ],
                    'numeric' => false,
                    'typeDate' => true,
                ],
                "REF_PHONE" => [
                    'message' => 'Request Parameter [REF_PHONE]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุเบอร์โทรศัพท์' . ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'ผู้ค้ำประกัน' : 'บุคคลอ้างอิง'),
                    //     'EN' => 'Please identify '. ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1 ? 'reference' : 'guarantor'). ' of phone number'
                    // ],
                    'numeric' => false,
                    'Guarantor' => true,
                ],
                "EMAILGuarantor" => [
                    'message' => 'Request Parameter [EMAILGuarantor]',
                    // 'message' => [
                    //     'TH' => 'กรุณาระบุอีเมลผู้ค้ำประกัน',
                    //     'EN' => 'Please identify reference of E-mail'
                    // ],
                    'numeric' => false,
                    'Guarantor' => true,
                    'NotGuarantor' => true,
                ],
            ];

            if ($GET_FLAG_GUARANTOR[0]->FLAG_GUARANTOR == 1) {
                foreach ($validateGuarantor as $key => $value) {
                    if (isset($value['Guarantor'])) {
                        if (!isset($data[$key])) {
                            throw new Exception($value['message']);
                        }

                        if (isset($value['tax_id'])) {
                            if (strlen($data[$key]) != 13) {
                                throw new Exception("TAX ID must have 13 digits.");
                                // $mes_error = (object)[
                                //     'TH' => 'หมายเลขบัตรประชาชนต้องมี 13 หลัก',
                                //     'EN' => 'TAX ID must have 13 digits'
                                // ];
                                // throw new Exception(json_encode($mes_error));
                            }
                        }

                        if ($value['numeric'] == true) {
                            if (!is_numeric($data[$key])) {
                                throw new Exception('Request Type of $(int) [' . $key . '] ');
                                // $mes_error = new stdClass;
                                // foreach ($value['message'] as $key => $value) {
                                //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                                //     $mes_error->$key = $txt;
                                // }
                                // throw new Exception(json_encode($mes_error));
                            }
                        }
                    }
                }
            } else {
                foreach ($validateGuarantor as $key => $value) {
                    if (!isset($value['NotGuarantor'])) {
                        if (!isset($data[$key])) {
                            throw new Exception($value['message']);
                        }

                        if (isset($value['tax_id'])) {
                            if (strlen($data[$key]) != 13) {
                                throw new Exception("TAX ID must have 13 digits.");
                                // $mes_error = (object)[
                                //     'TH' => 'หมายเลขบัตรประชาชนต้องมี 13 หลัก',
                                //     'EN' => 'TAX ID must have 13 digits'
                                // ];
                                // throw new Exception(json_encode($mes_error));
                            }
                        }

                        if ($value['numeric'] == true) {
                            if (!is_numeric($data[$key])) {
                                throw new Exception('Request Type of $(int) [' . $key . '] ');
                                // $mes_error = new stdClass;
                                // foreach ($value['message'] as $key => $value) {
                                //     $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                                //     $mes_error->$key = $txt;
                                // }
                                // throw new Exception(json_encode($mes_error));
                            }
                        }

                        if (isset($value['typeDate'])) {
                            // $time = strtotime($data[$key]);
                            // $newformat = date('Y-m-d',$time);
                            // var_dump($newformat);
                            if (strtotime($data[$key]) == false) {
                                // throw new Exception('Request Type of $(date) [' . $key . '] ');
                                $mes_error = new stdClass;
                                foreach ($value['message'] as $key => $value) {
                                    $txt = ($key == "TH" ? $value . "ให้ถูกต้อง" : $value);
                                    $mes_error->$key = $txt;
                                }
                                throw new Exception(json_encode($mes_error));
                            }
                        }

                    }
                }
            }

            // dd($GET_PROSPECT_CUSTOMER);
            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
            // Check Cerate Or Update
            $UpdateTime = null;
            if (isset($GET_PROSPECT_CUSTOMER[0]->CREATE_DATE)) {
                $UpdateTime = $date_now;
            }
            // dd($GET_PROSPECT_CUSTOMER[0]->CREATE_DATE );

            
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


            $IDCARD_FILE = "<file><name>Img-IDCard-" . $data['TAX_ID'] . "</name><content>" . $this->ResizeImage($data['IDCARD_FILE']) . "</content></file>";
            $STUDENTCARD_FILE = "<file><name>Img-StudentCard-" . $data['TAX_ID'] . "</name><content>" . $this->ResizeImage($data['STUDENTCARD_FILE']) . "</content></file>";
            $FACE_FILE = "<file><name>Img-Face-" . $data['TAX_ID'] . "</name><content>" . $this->ResizeImage($data['FACE_FILE']) . "</content></file>";

            // $IDCARD_FILE = "<file><name>Img-IDCard-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['IDCARD_FILE']))."</content></file>";
            // $STUDENTCARD_FILE = "<file><name>Img-StudentCard-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['STUDENTCARD_FILE']))."</content></file>";
            // $FACE_FILE = "<file><name>Img-Face-".$data['TAX_ID']."</name><content>".base64_encode(file_get_contents($data['FACE_FILE']))."</content></file>";

            // $IDCARD_FILE = "";
            // $STUDENTCARD_FILE = "";
            // $FACE_FILE = "";

            // dd($data['IDCARD_FILE']);
            // dd($IDCARD_FILE);

            DB::table('dbo.PROSPECT_CUSTOMER')
                ->where('PST_CUST_ID', $data['PST_CUST_ID'])
                ->where('QUOTATION_ID', $data['QUOTATION_ID'])
                ->where('TAX_ID', $data['TAX_ID'])
                ->update([
                    'PREFIX' => $data['PREFIX'],
                    'PREFIX_OTHER' => isset($data['PREFIX_OTHER']) ? $data['PREFIX_OTHER'] : null,
                    'FIRST_NAME' => $data['FIRST_NAME'],
                    'LAST_NAME' => $data['LAST_NAME'],
                    'TAX_ID' => $data['TAX_ID'],
                    'STUDENT_ID' => isset($data['STUDENT_ID']) ? $data['STUDENT_ID'] : null,
                    'BIRTHDAY' => $BIRTHDAY,
                    'AGE' => isset($data['AGE']) ? $data['AGE'] : null,
                    'SEX' => $data['SEX'],
                    'MARITAL_STATUS' => $data['MARITAL_STATUS'],
                    'PHONE' => $data['PHONE'],
                    'EMAIL' => $data['EMAIL'],
                    'FACEBOOK' => isset($data['FACEBOOK']) ? $data['FACEBOOK'] : null,
                    'LINEID' => isset($data['LINEID']) ? $data['LINEID'] : null,
                    'OCCUPATION_CODE' => $data['OCCUPATION_CODE'],
                    'MAIN_INCOME' => $data['MAIN_INCOME'],
                    'UNIVERSITY_PROVINCE' => $data['UNIVERSITY_PROVINCE'],
                    'UNIVERSITY_DISTRICT' => $data['UNIVERSITY_DISTRICT'],
                    'UNIVERSITY_NAME' => $data['UNIVERSITY_NAME'],
                    'UNIVERSITY_OTHER' => isset($data['UNIVERSITY_OTHER']) ? $data['UNIVERSITY_OTHER'] : null,
                    'CAMPUS_NAME' => isset($data['CAMPUS_NAME']) ? $data['CAMPUS_NAME'] : null,
                    'FACULTY_NAME' => $data['FACULTY_NAME'],
                    'FACULTY_OTHER' => isset($data['FACULTY_OTHER']) ? $data['FACULTY_OTHER'] : null,
                    'SUBJECT_NAME' => isset($data['SUBJECT_NAME']) ? $data['SUBJECT_NAME'] : null,
                    'LEVEL_TYPE' => $data['LEVEL_TYPE'],
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
                    'STUDENTCARD_FILE' => $STUDENTCARD_FILE,
                    'FACE_FILE' => $FACE_FILE,
                    'URLMAP' => $data['URLMAP'],
                    'CREATE_DATE' => $GET_PROSPECT_CUSTOMER[0]->CREATE_DATE == null ? $date_now : $GET_PROSPECT_CUSTOMER[0]->CREATE_DATE,
                    'UPDATE_DATE' => null,
                    'NAME_MAKE' => "API",
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
                DB::table('dbo.PROSPECT_CUSTOMER')
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
                    ]);
            }


            return response()->json(array(
                'Code' => '9999',
                'status' => 'Success',
                'data' => [
                    'TAX_ID' => $data['TAX_ID'],
                    'QUOTATION_ID' => $data['QUOTATION_ID'],
                    'PST_CUST_ID' => $data['PST_CUST_ID']
                ]
            ));
        } catch (Exception $e) {;
            // dd($e->getMessage());
            // $getPrevious = $e->getPrevious();
            if ($e->getPrevious() != null) {
                return response()->json(array(
                    'Code' => '0003',
                    'status' => 'Error',
                    'message' => $e->getPrevious()->getMessage()
                    // 'message' => [
                    //     'TH' => 'ข้อมูลไม่ถูกต้อง โปรดลองอีกครั้ง',
                    //     'EN' => 'Data invalid. Please try again'
                    // ]
                ));
            }

            return response()->json(array(
                'Code' => '0013',
                'status' => 'Error',
                'message' => $e->getMessage()
            ));
        }
    }
}
