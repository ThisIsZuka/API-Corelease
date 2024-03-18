<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\NationalCreditBureau;
use App\Models\MT_STATUS;
use App\Models\CUSTOMER_CARD;
use App\Models\CONTRACT;
use App\Models\PERSON;
use App\Models\PRODUCT;
use App\Models\REPAYMENT;
use App\Models\ADDRESS;
use App\Models\MT_PREFIX;
use App\Models\MT_PROVINCE;
use App\Models\MT_DISTRICT;
use App\Models\MT_SUB_DISTRICT;
use App\Models\NBC_WORK_QUEUE;
use App\Models\NBC_WORK_QUEUE_DETAILED;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Symfony\Component\String\b;

class NcbFormat extends Command
{
    public const TUDF = "TUDF";
    public const TRAILER = "TRLR";
    public const PathFile = "/file_location/report_ncb/13/";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:NcbFormat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'NCB ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->member_data = [
            "membercode" => env("NCB_MEMBER_CODE"),
            "membername" => env("NCB_MEMBER_NAME"),
            "password" => env("NCB_MEMBER_PASSWORD"),
            "futureuse" => env("NCB_FUTURE_USE"),
            "memberdata" => env("NCB_MEMBER_DATA")
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // SELECT * FROM CUSTOMER_CARD
        // WHERE APPLICATION_NUMBER ='223824'
        // order by INSTALL_NUM

        // SELECT  * FROM CUSTOMER_CARD
        // WHERE APPLICATION_NUMBER  ='199622'
        // order by INSTALL_NUM

        // SELECT  * FROM CONTRACT
        // WHERE APP_ID  ='199622'
        // $this->InstallmentFrequency (3);
        //  echo $this->NumberOfDaysPastDue(199622);
        // $dateNow = date("Y-m-d");
        // $AmountPastDue = $this->AmountPastDue(187631,$dateNow);
        // print_r( $AmountPastDue);
        // $MaritalStatus = $this->PERSON(100159);
        // print_r($MaritalStatus);
        // $Address =  $this->SetNewAddress(699);
        // print_r( $Address);
        // echo "InstallmentAmount = " . $this->InstallmentAmount(232513);
        // echo "\n";
        // echo "UnitMakeAndUnitModel = " . $this->UnitMakeAndUnitModel(232513);
        // $Address_Line_1 = 'รวมเพลงเหงาเพราะความเศร้า';
        // $Address_Line_2 = 'เลือกเราเสมอยังมีเธออยู่ในทุกเพลงที่เราเคยฟังด้วยกัน';
        // $Address_Line_3 = 'รวมเพลงเพราะๆฮิตๆฟังเพลงอกหัก1ชั่วโมงเอาไว้เปิดฟังยาวๆฟังเพลินๆตอนขับรถ';
        // print_r($this->SetNewAddress($Address_Line_1,$Address_Line_2,$Address_Line_3));

        // $NationalCreditBureau =  NationalCreditBureau::get();

        // echo "CreditLimitOriginalLoanAmount : ";
        // echo $CreditLimitOriginalLoanAmount = $this->CreditLimitOriginalLoanAmount(232513);
        // echo "\n";
        // echo "AmountOwedCreditUse : ";
        // echo $AmountOwedCreditUse = $this->AmountOwedCreditUse(232513);
        $this->setdata();
        die;


        $is_pass = true;
        $Version = 13; /////default  
        $content_body = '';
        $TUDF_Header_Section = '';
        $TUDF_Name_Section = '';
        $TUDF_Id_Section = '';
        $TUDF_Address_Section = '';
        $TUDF_Account_Section = '';
        $TUDF_Trailer_section =  '';
        $MemberData = $content_all_data = '';


        // $NationalCreditBureau; 
        // ให้รันแต่ละเดือน where('As_Of_Date',)->
        $NationalCreditBureau = NationalCreditBureau::get();

        $TracingNumber = count($NationalCreditBureau); //จำนวน record ที่นำส่งข้อมูล
        $TextFileName = 'TUCRS-' . $this->member_data['membername'] . '-' . date('Y-m') . '.txt';


        // header segment TUDF
        if ($is_pass) {

            $TUDF_Header_Section = NcbFormat::TUDF . $Version;

            $TUDF_Header_Section .= str_pad($this->member_data['membercode'], 10, " ", STR_PAD_RIGHT);
            // set Position = 33 digi 
            // Cycle Identification ระบุให้ ใส่ค่าเป็นช่องว่าง Fixed Length 2
            $TUDF_Header_Section .= str_pad($this->member_data['membername'], 16, " ", STR_PAD_RIGHT);

            // Cycle Identification ระบุให้ ใส่ค่าเป็นช่องว่าง ทั้งหมด 2 ตำแหน่ง
            $TUDF_Header_Section .= "  ";

            // Position 35 เป็น AsOfDate Fixed Length = 8 YYYYMMDD (ปี ค.ศ.) ต้องระบุบน้อยกว่าเดือนปัจจุบันที่นำส่งเทส

            $TUDF_Header_Section .=  date("Ymt", strtotime(date('Ymd', strtotime("-1 month"))));
            // Position 43 PASSWORD Fixed Length 8 
            $TUDF_Header_Section .= $this->member_data['password'];

            // Position 51 Future Use Fixed Length = 2 ระบุค่าเป็น “0” จำนวน 2 หลัก
            $TUDF_Header_Section .= '00';

            // Position 53 Member Data Fixed Length = 40 
            // ระบุค่าที่สมาชิกใช้สำหรับใช้เพื่อระบุรายละเอียดเพิ่มเติม ถ้าไม่มีให้ใส่ช่องว่าง 40 ตัว
            $TUDF_Header_Section .= str_pad($MemberData, 40, " ", STR_PAD_RIGHT);

            //Position 93 Tracing number Fixed Length = 8 ระบุจำนวน record ที่นำส่ง
            $TUDF_Header_Section .= str_pad($TracingNumber, 8, "0", STR_PAD_LEFT);

            //Total Characters 100 digi
            $TUDF_Header_Section .= "\r\n";
            //end 
        }

        foreach ($NationalCreditBureau as $data) {

            ////Name segment PN
            if ($is_pass) {

                $TUDF_Name_Section = 'PN03N01';
                //FieldTag = 01 Family Name1
                if ($data->Family_Name_1) {
                    $TUDF_Name_Section .=  '01' . str_pad(mb_strlen($data->Family_Name_1, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Family_Name_1;
                }

                // Family_Name_2 = 02 Family Name2
                if ($data->Family_Name_2) {
                    $TUDF_Name_Section .=  '02' . str_pad(mb_strlen($data->Family_Name_2, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Family_Name_2;
                }

                //FieldTag = 04 First Name
                if ($data->First_Name) {
                    $TUDF_Name_Section .=  '04' . str_pad(mb_strlen($data->First_Name, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->First_Name;
                }

                if ($data->Middle) {
                    // Middle FieldTag = 05  maxLength = 30 fieldtype = AW
                    $TUDF_Name_Section .=  '05' . str_pad(strlen($data->Middle), 2, "0", STR_PAD_LEFT) . $data->Middle;
                }

                //FieldTag = 06 Martial Status Field Length 04
                if ($data->Martial_Status) {
                    $TUDF_Name_Section .=  '0604' . str_pad($data->Martial_Status, 4, "0", STR_PAD_LEFT);
                }

                //FieldTag = 07 Date of Birth
                if ($data->Date_Of_Birth) {
                    $TUDF_Name_Section .=  '07' . str_pad(strlen($data->Date_Of_Birth), 2, "0", STR_PAD_LEFT) . $data->Date_Of_Birth;
                }

                //FieldTag = 10 Nationlality Field Length 02
                if ($data->Nationlality) {
                    $TUDF_Name_Section .=   '10' . str_pad(strlen($data->Nationlality), 2, "0", STR_PAD_LEFT) . $data->Nationlality;
                    // '10' . str_pad(strlen($data->Nationlality), 2, "0", STR_PAD_LEFT) . str_pad($data->Nationlality, 2, "0", STR_PAD_LEFT);

                }

                //FieldTag = 11 Number of children 02
                if ($data->Number_Of_Children) {
                    $TUDF_Name_Section .=  '11' . str_pad(strlen($data->Number_Of_Children), 2, "0", STR_PAD_LEFT) . str_pad($data->Number_Of_Children, 2, "0", STR_PAD_LEFT);
                }

                //FieldTag = 13 Occupation Field Length 02
                if ($data->Occupation) {
                    $TUDF_Name_Section .=  '13' . str_pad(strlen($data->Occupation), 2, "0", STR_PAD_LEFT) . $data->Occupation;
                }

                //FieldTag = 15 Occupation Field Length 1
                if ($data->Customer_Type_Field) {
                    $TUDF_Name_Section .=  '15' . str_pad(strlen($data->Customer_Type_Field), 2, "0", STR_PAD_LEFT) . $data->Customer_Type_Field;
                }
            }

            ////ID segment ID
            if ($is_pass) {

                $TUDF_Id_Section = 'ID03ID1';

                //Field Tag = 01  ID Type 
                if ($data->ID_Type) {
                    $TUDF_Id_Section .=  '01' . str_pad($data->ID_Type, 2, "0", STR_PAD_LEFT);
                }
                //ID Type 01 ID Number Max = 20 digi
                if ($data->ID_Number) {
                    $IdNumber = (strlen($data->ID_Number) > 20) ? substr($data->ID_Number, 0, 20) :  $data->ID_Number;
                    $TUDF_Id_Section .=  '02' . str_pad(strlen($IdNumber), 2, "0", STR_PAD_LEFT) . $IdNumber;
                }
            }

            ////address segment PA
            if ($is_pass) {
                $TUDF_Address_Section = 'PA03A01';

                if ($data->Address_Line_1) {
                    $TUDF_Address_Section .= '01' . str_pad(mb_strlen($data->Address_Line_1, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Address_Line_1;
                }

                if ($data->Address_Line_2) {
                    $TUDF_Address_Section .= '02' . str_pad(mb_strlen($data->Address_Line_2, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Address_Line_2;
                }

                if ($data->Address_Line_3) {
                    $TUDF_Address_Section .= '03' . str_pad(mb_strlen($data->Address_Line_3, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Address_Line_3;
                }

                if ($data->Sub_District) {
                    $TUDF_Address_Section .= '04' . str_pad(mb_strlen($data->Sub_District, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Sub_District;
                }

                if ($data->District) {
                    $TUDF_Address_Section .= '05' . str_pad(mb_strlen($data->District, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->District;
                }

                if ($data->Province) {
                    $TUDF_Address_Section .= '06' . str_pad(mb_strlen($data->Province, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Province;
                }

                if ($data->Country) {
                    $TUDF_Address_Section .= '07' . str_pad(mb_strlen($data->Country, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Country;
                }

                if ($data->Postal_Code) {
                    $TUDF_Address_Section .= '08' . str_pad(mb_strlen($data->Postal_Code, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Postal_Code;
                }
            }

            //// Account segment (TL)
            if ($is_pass) {

                $TUDF_Account_Section = 'TL04T001';
                $TUDF_Account_Section .= '01' . str_pad(mb_strlen($this->member_data['membercode'], 'utf-8'), 2, "0", STR_PAD_LEFT) . $this->member_data['membercode'];
                $TUDF_Account_Section .= '02' . str_pad(mb_strlen($this->member_data['membername'], 'utf-8'), 2, "0", STR_PAD_LEFT) . $this->member_data['membername'];

                if ($data->Current_New_Account_Number) {
                    // Field Tag = 03 ระบุค่าหมายเลขบัญชีสินเชื่อของลูกหนี้
                    $TUDF_Account_Section .= '03' . str_pad(mb_strlen($data->Current_New_Account_Number, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Current_New_Account_Number;
                }

                if ($data->Account_Type) {
                    // Field Tag = 04 ระบุค่าประเภทสินเชื่อของบัญชีนั้นๆตามตารางที่ NCB กำหนดใน Appendix A เช่น 05 Personal Loan
                    $TUDF_Account_Section .= '04' . str_pad(mb_strlen($data->Account_Type, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Account_Type;
                }

                if ($data->Ownership_Indicator) {
                    // Field Tag = 05 ระบุค่าลักษณะของบัญชีสินเชื่อตามตารางที่ NCB กำหนดใน Appendix A เช่น 1 กู้เดี่ยว 2 บัตรเสริม 4 กู้ร่วม
                    $TUDF_Account_Section .= '05' . str_pad(mb_strlen($data->Ownership_Indicator, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Ownership_Indicator;
                }

                if ($data->Currency_Code) {
                    // Field Tag = 06 ให้ใส่ค่า THB เท่านั้น
                    $TUDF_Account_Section .= '06' . str_pad(mb_strlen($data->Currency_Code, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Currency_Code;
                }

                if ($data->Date_Account_Opened) {
                    // Field Tag = 08 ระบุวันที่เปิดบัญชีโดยมีรูปแบบเป็น YYYYMMDD (ปี ค.ศ.)
                    $TUDF_Account_Section .= '08' . str_pad(mb_strlen($data->Date_Account_Opened, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Date_Account_Opened;
                }

                if ($data->Date_Of_Last_Payment) {
                    //Field Tag = 09 ระบุวันที่ลูกหนี้ชำระเงินครั้งสุดท้าย โดยมีรูปแบบเป็ น YYYYMMDD (ปี ค.ศ.)
                    $TUDF_Account_Section .= '09' . str_pad(mb_strlen($data->Date_Of_Last_Payment, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Date_Of_Last_Payment;
                }

                if ($data->Date_Account_Closed) {
                    //Field Tag = 10 ระบุวันที่ปิดบัญชีที่มีการปิดในรอบเดือนนั้น โดยมีรูปแบบเป็น YYYYMMDD (ปี ค.ศ.)
                    $TUDF_Account_Section .= '10' . str_pad(mb_strlen($data->Date_Account_Closed, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Date_Account_Closed;
                }

                if ($data->As_Of_Date) {
                    //Field Tag = 11 ระบุวันที่สิ้นงวดของข้อมูล หรือวันที่ update บัญชีครั้งสุดท้ายโดยมี รูปแบบเป็ น YYYYMMDD (ปี ค.ศ.) หาก field นี้ไม่ระบุค่าระบบจะแทนด้วยค่า default คือ วันที่ As of Date ใน Header Segment
                    $TUDF_Account_Section .= '11' . str_pad(mb_strlen($data->As_Of_Date, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->As_Of_Date;
                }

                if ($data->Credit_Limit_Original_Loan_Amount) {
                    //Field Tag = 12 วงเงินที่ได้รับอนุมัติตามสัญญา ไม่มีจุดทศนิยม
                    $TUDF_Account_Section .= '12' . str_pad(mb_strlen($data->Credit_Limit_Original_Loan_Amount, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Credit_Limit_Original_Loan_Amount;
                }

                if ($data->Amount_Owed) {
                    //Field Tag = 13 ระบุค่ายอดหนี้ของบัญชีสินเชื่อ ณ วันที่ปรากฏใน Field As ofDate ซึ่งกรณีติดลบให้รายงานค่าเป็นศูนย์ ไม่มีจุดทศนิยม
                    $TUDF_Account_Section .= '13' . str_pad(mb_strlen($data->Amount_Owed, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Amount_Owed;
                }

                if ($data->Amount_Past_Due) {
                    // Field Tag = 14 ระบุค่ายอดเงินเกินกำหนดชำระของบัญชีสินเชื่อ ณ วันที่ปรากฏใน 
                    // field As of Date ซึ่งกรณีติดลบให้รายงานค่าเป็นศูนย์ ไม่มีจุดทศนิยม จะสอดคล้องกับวันที่ผิดนัดชำระหนี้
                    $TUDF_Account_Section .= '14' . str_pad(mb_strlen($data->Amount_Past_Due, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Amount_Past_Due;
                }

                if ($data->Number_Of_Days_Past_Due_Delinquency_Status) {
                    //Field Tag = 15 ระบุจำนวนวันค้างชำระตาม Delay Payment Code (นับจากลูกค้าผิดนัดชำระจนถึง ณ งวดสินเดือนที่ส่งข้อมูล แล้วนำมา Mapping กับตาราง)
                    // $NumberOfDaysPastDueDelinquencyStatus = 120;
                    $TUDF_Account_Section .= '15' . str_pad(mb_strlen($data->Number_Of_Days_Past_Due_Delinquency_Status, 'utf-8'), 3, "0", STR_PAD_LEFT) . $data->Number_Of_Days_Past_Due_Delinquency_Status;
                }

                if ($data->Default_Date) {
                    //Field Tag = 19 ระบุวันที่ เริ่มต้นผิดนัดชำระหนี้ YYYMMDD เมื่อมีการผิดนัดชำระหนี้กรณีไม่มีการผิดนัดชำระหนี้ให้ระบุ 19000101
                    $TUDF_Account_Section .= '19' . str_pad($data->Default_Date, 2, "0", STR_PAD_LEFT) . $data->Default_Date;
                }

                if ($data->Installment_Frequency) {
                    //Field Tag = 20 ระบุความถี่ในการชำระตามค่าที่กำหนด 0 = Unspecified 1 = Weekly 2 = Biweekly 3 = Monthly 4 = Bimonthly 5 = Quarterly 6 = Daily 7 = Special use (lump sum etc.) 8 = Semi-yearly 9 = Yearly
                    $TUDF_Account_Section .= '20' . str_pad($data->Installment_Frequency, 1, "0", STR_PAD_LEFT) . $data->Installment_Frequency;
                }

                if ($data->Installment_Amount) {
                    //Field Tag = 21 ระบุความถี่ในการชำระตามค่าที่กำหนด 0 = Unspecified 1 = Weekly 2 = Biweekly 3 = Monthly 4 = Bimonthly 5 = Quarterly 6 = Daily 7 = Special use (lump sum etc.) 8 = Semi-yearly 9 = Yearly
                    $TUDF_Account_Section .= '21' . str_pad($data->Installment_Amount, 1, "0", STR_PAD_LEFT) . $data->Installment_Amount;
                }

                if ($data->Installment_Number_Of_Payments) {
                    //Field Tag = 22 ระบุจำนวนงวดที่ชำระ สำหรับสินเชื่อที่มีการผ่อนเป็นงวดๆ
                    $TUDF_Account_Section .= '22' . str_pad($data->Installment_Number_Of_Payments, 9, "0", STR_PAD_LEFT) . $data->Installment_Number_Of_Payments;
                }

                if ($data->Account_Status) {
                    //Field Tag = 23 ระบุค่าสถานะบัญชีตามค่าที่กำหนด
                    $TUDF_Account_Section .= '23' . str_pad(mb_strlen($data->Account_Status, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Account_Status;
                }

                if ($data->Loan_Objective) {
                    // Field Tag = 32
                    // ระบุวัตถุประสงค์การกู้ โดยระบุรหัสวัตุประสงค์การกู้
                    // 44444 สำหรับบัญชีคลินิกแก้หนี้
                    // 55555 สำหรับบัญชีสินเชื่อส่วนบุคคลภายใต้การกำกับ (ปว.58)
                    // 66666 สำหรับบัญชีสินเชื่อเพื่อผู้สูงอายุโดยมีที่อยู่อาศัยเป็นหลักประกัน (Reverse Mortgage)
                    // '77777' สำหรับบัญชีสินเชื่อพิโกไฟแนนซ์ (PicoFinance)
                    // 88888 สำหรับสินเชื่อดิจิทัล (Digital PersonalLoan)
                    // 55554 โอนขายหนี้บางส่วน
                    $TUDF_Account_Section .= '32' . str_pad(mb_strlen($data->Loan_Objective, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Loan_Objective;
                }

                if ($data->Date_Of_Last_Debt_Restructuring) {
                    // Field Tag = 36
                    // กรณีบัญชีค้างชำระเกิน 90 วัน และมีการปรับปรุงโครงสร้างหนี้ให้รายงานวันที่ปรับปรุงโครงสร้างหนี้ 
                    // หมายเหตุ: กรณียังไม่มีการปรับปรุงโครงสร้างหนี้สมาชิกนำส่งค่า 19000101 (ไม่มีข้อมูล ระบบจะไม่แสดงผล) ระบบจะUpdate ข้อมูล Field ดังกล่าวตามที่สมาชิกน าส่งทุกครั้ง
                    $TUDF_Account_Section .= '36' . str_pad(mb_strlen($data->Date_Of_Last_Debt_Restructuring, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Date_Of_Last_Debt_Restructuring;
                }

                if ($data->Percent_Payment) {
                    // Field Tag = 37
                    // ระบุเปอร์เซนต์ขั้นต่ำที่ชำระ (สำหรับบัตรเครดิต/สินเชื่อบุคคลมีการการชำระตามขั้นต่ำ/อื่นๆ)
                    $TUDF_Account_Section .= '37' . str_pad(mb_strlen($data->Percent_Payment, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Percent_Payment;
                }

                if ($data->Type_Of_Credit_Card) {
                    // Field Tag = 38
                    // ระบุประเภทบัตรเครดิต(สำหรับบัตรเครดิต) ตามตารางประเภทบัตร (Appendix A)
                    $TUDF_Account_Section .= '38' . str_pad(mb_strlen($data->Type_Of_Credit_Card, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Type_Of_Credit_Card;
                }

                if ($data->Number_Of_Co_Borrower) {
                    // Field Tag = 39
                    // ระบุจำนวนผู้กู้ร่วม กรณีกู้เดี่ยวระบุเป็น 00 กรณี Ownership เป็น กู้ร่วมให้ระบุจำนวนผู้กู้ร่วม
                    $TUDF_Account_Section .= '39' . str_pad(mb_strlen($data->Number_Of_Co_Borrower, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Number_Of_Co_Borrower;
                }

                if ($data->Unit_Make) {
                    //Field Tag = 40
                    //ระบุยี่ห้อของสินค้า Required สำหรับสินเชื่อให้เช่าแบบลิสซิ่ง หรือเช่าซื้อ
                    $TUDF_Account_Section .= '40' . str_pad(mb_strlen($data->Unit_Make, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Unit_Make;
                }


                if ($data->Unit_Model) {
                    //Field Tag = 41
                    //ระบุรุ่นของสินค้า Required สำหรับสินเชื่อให้เช่าแบบลิสซิ่ง หรือเช่าซื้อ
                    // $UnitModel = 'iPhone 13 Pro M';
                    $TUDF_Account_Section .= '41' . str_pad(mb_strlen($data->Unit_Model, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Unit_Model;
                }

                if ($data->Credit_Limit_Type_Flag) {
                    //ระบุรหัสตามที่ NCB กำหนดแต่ละสมาชิกให้นำส่งกรณีบัญชีมีการใช้วงเงินร่วมมากกว่า 1 บัญชีในลูกหนี้นั้นๆ หรือบัตรเครดิตที่ใช้วงเงินร่วมกัน
                    $TUDF_Account_Section .= '42' . str_pad(mb_strlen($data->Credit_Limit_Type_Flag, 'utf-8'), 2, "0", STR_PAD_LEFT) . $data->Credit_Limit_Type_Flag;
                }
            }

            // End of Subject (ES) [P] 6 Fixed
            // ระบุค่าเป็น “ES02**” โดย
            // ES = Segment Tag
            // 02 = ความยาวข้อมูล
            // ** = ข้อมูลบอกถึง End Characters
            $content_body .= $TUDF_Name_Section . $TUDF_Id_Section . $TUDF_Address_Section . $TUDF_Account_Section . "ES02**\n";
        }

        // Trailer Segment (TRLR) – Fixed Length
        if ($is_pass) {
            // End of File
            $TUDF_Trailer_section .= NcbFormat::TRAILER;
        }

        $content_all_data = $TUDF_Header_Section .  $content_body . $TUDF_Trailer_section;

        $thisfile = fopen(public_path() . NcbFormat::PathFile . $TextFileName, 'a+');
        fwrite($thisfile, iconv('UTF-8', 'TIS-620//TRANSLIT//IGNORE', $content_all_data));
        // fwrite($thisfile, $content_all_data);
        fclose($thisfile);

        return 0;
    }


    public function setdata($from = '2024-01-01', $to = '2024-01-31', $PersonID = null, $AppID = null, $ContractID = null)
    {
        ini_set('memory_limit', '-1');
        $date_now = date("Y-m-d");
        $date = Carbon::now(); //returns current day
        echo "from ";
        echo  $from ? $from : Carbon::now()->format('Y-m-01');
        echo " ==== to ==== ";
        echo   $to ? $to : Carbon::now()->format('Y-m-t');
        echo "\n";

        $CusCard = array();
        $is_pass = false;
        $timestamp = time();
        $month = date('m', strtotime($from));
        $year = date('Y', strtotime($from));
        $guId = Str::uuid();
        
       print_r($guId);
        //$checkQueue =  $this->checkQueue();
        // if ( $checkQueue["Code"] !== "0" ) {
        //     $is_pass = false;
        //     exit();
        // }


        //->WHERE("ID","<>",$checkQueue["ID"])
        // $CheckQueueWork = NBC_WORK_QUEUE::WHERE("IN_PROGRESS",1)->WHERE("ID","<>",$checkQueue["ID"])->get();
        // foreach ($CheckQueueWork as  $DataQueueWork) {
        //     if( $DataQueueWork->IN_PROGRESS == 1) {
        //         echo "มีคิวอื่นทำงานอยู่";
        //         $is_pass = false;
        //         exit();
        //     }
        // }

        //// เก็บ lot_ref ที่จะรันใว้ 
        // $guId = $checkQueue["Lot_ref"];
        $limit = 100;
        $page = 1;
        $Line = 0;
        $do = 0;
        do {
            echo "============== (Page " . $page . " ) ==============";
            echo "\n";

            $Contract = CONTRACT::Where('MAKE_DATE', "<=", $to)
                ->Where(function ($query) {
                    $query->WHERE('CUSTOMER_NAME', 'NOT LIKE', DB::raw("N'%ทดสอบ%'"))
                        ->orWHERE('CUSTOMER_NAME', 'NOT LIKE', DB::raw("N'%TesT%'"))
                        ->orWHERE('CUSTOMER_NAME', 'NOT LIKE', DB::raw("N'%test%'"))
                        ->orWHERE('CUSTOMER_NAME', 'NOT LIKE', DB::raw("N'%TEST%'"))
                        ->orWHERE('CUSTOMER_NAME', 'NOT LIKE', DB::raw("N'%Test%'"));
                })
                // ->where('CONTRACT_ID', 89593)
                ->orderBy('CONTRACT_ID', 'DESC')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->get();

            foreach ($Contract as $DataContract) {

                echo "No. " . ($Line += 1) . " | ContractID : " . $DataContract->CONTRACT_ID;

                $CusCard = CUSTOMER_CARD::WHERE('CONTRACT_ID', $DataContract->CONTRACT_ID)
                    ->WHERE('DUEDATE', '>=', $from)
                    ->WHERE('DUEDATE', '<=', $to)
                    ->orderBy('INSTALL_NUM', 'ASC')
                    ->first();

                if ($CusCard != null) {

                    // foreach ($CusCard as $DataCusCard) {

                    $NationalCreditBureau = new NationalCreditBureau;
                    $Person  = $this->Person($DataContract->APP_ID); //เก็บเป็นตั '';
                    $Address =  $this->SetNewAddress($Person["Person_ID"]);
                    $AmountPastDue = $this->AmountPastDue($DataContract->APP_ID, $date_now);
                    $AccountStatus = $this->AccountStatus($DataContract->STATUS_ID, $AmountPastDue["DPD"], $month, $year, $AmountPastDue["AmountPastDueStatus"], $customer_type = '01');
                    $NationalCreditBureau->Family_Name_1 = $this->LanguageEnAndSetUpper($Person["Family_Name_1"]);
                    $NationalCreditBureau->Family_Name_2 = $this->LanguageEnAndSetUpper($Person["Family_Name_2"]);
                    $NationalCreditBureau->First_Name = $this->LanguageEnAndSetUpper($Person["First_Name"]);
                    $NationalCreditBureau->Middle = null;
                    $NationalCreditBureau->Marital_Status = $Person["Marital_Status"]; //เก็บเป็นตัวเลข
                    $NationalCreditBureau->Date_Of_Birth = $Person["BirthDay"];
                    $NationalCreditBureau->Gender = $Person["Sex"];
                    $NationalCreditBureau->Title_Prefix =  $Person["Prefix"];
                    $NationalCreditBureau->Nationality = $Person["Nationality"];
                    $NationalCreditBureau->Number_Of_Children = 0;
                    $NationalCreditBureau->Spouse_Name = null;
                    $NationalCreditBureau->Occupation = 0;
                    $NationalCreditBureau->Customer_Type_Field = 1;
                    $NationalCreditBureau->ID_Type = $this->IdTypeNCB($Person["Card_code"], $Person["Nationality_Code"]);
                    $NationalCreditBureau->ID_Number = $Person["TAX_ID"];
                    $NationalCreditBureau->ID_Issue_Country = "TH";
                    $NationalCreditBureau->Address_Line_1 = $Address["Address_1"];
                    $NationalCreditBureau->Address_Line_2 = $Address["Address_2"] ?? '';
                    $NationalCreditBureau->Address_Line_3 = $Address["Address_3"] ?? '';
                    $NationalCreditBureau->Sub_District = $Address["SubDistrict"];
                    $NationalCreditBureau->District =  $Address["District"];
                    $NationalCreditBureau->Province = $Address["Province"];
                    $NationalCreditBureau->Telephone = $Person["Phone"];
                    $NationalCreditBureau->Postal_Code = $Address["PostalCode"];
                    $NationalCreditBureau->Country = 'TH';
                    $NationalCreditBureau->Telephone_Type = "2";
                    $NationalCreditBureau->Address_Type = 0;
                    $NationalCreditBureau->Residential_Status = 0;
                    $NationalCreditBureau->Current_New_Member_Code = $this->CurrentNewMemberCode();
                    $NationalCreditBureau->Current_New_Member_Name = $this->CurrentNewMemberName();
                    $NationalCreditBureau->Current_New_Account_Number = $DataContract->CONTRACT_NUMBER;
                    $NationalCreditBureau->Account_Type = "21";
                    $NationalCreditBureau->Ownership_Indicator = 1;
                    $NationalCreditBureau->Currency_Code = "THB";
                    $NationalCreditBureau->Future_Use = 1;
                    $NationalCreditBureau->Date_Account_Opened = $this->SetFromFormatNCB($DataContract->CONTRACT_START);
                    $NationalCreditBureau->Date_Account_Closed = $this->SetFromFormatNCB($DataContract->CONTRACT_END); //$this->SetFromFormatNCB($date = false);
                    $NationalCreditBureau->As_Of_Date = $this->SetFromFormatNCB($DataContract->CONTRACT_END);
                    $NationalCreditBureau->Credit_Limit_Original_Loan_Amount = $this->CreditLimitOriginalLoanAmount($DataContract->APP_ID);
                    $NationalCreditBureau->Amount_Owed_Credit_Use = $this->AmountOwedCreditUse($DataContract->APP_ID);
                    $NationalCreditBureau->Old_Member_Code = null;
                    $NationalCreditBureau->Old_Member_Name = null;
                    $NationalCreditBureau->Old_Account_Number = null;
                    $NationalCreditBureau->Amount_Past_Due = $AmountPastDue["AmountPastDue"];
                    $NationalCreditBureau->Default_Date = $AmountPastDue["Default_Date"];
                    $NationalCreditBureau->Date_Of_Last_Payment = $AmountPastDue["DateOfLastPayment"];
                    $NationalCreditBureau->Number_Of_Days_Past_Due_Delinquency_Status = $this->NumberOfDaysPastDueDelinquencyStatus($AmountPastDue["DPD"]);
                    $NationalCreditBureau->Account_Status = $AccountStatus ?? 1;
                    $NationalCreditBureau->Installment_Frequency = $this->InstallmentFrequency(3);
                    $NationalCreditBureau->Installment_Amount = $this->InstallmentAmount($DataContract->APP_ID);
                    $NationalCreditBureau->Installment_Number_Of_Payments = $DataContract->INSTALL_NUM_FINAL;
                    $NationalCreditBureau->Loan_Object = null; //$this->LoanObjective($Loan_Object);
                    $NationalCreditBureau->Collateral_1 = null;
                    $NationalCreditBureau->Collateral_2 = null;
                    $NationalCreditBureau->Collateral_3 = null;
                    $NationalCreditBureau->Date_Of_Last_Debt_Restructuring = $this->SetFromFormatNCB($date = false); // วันที่ปรับโครงสร้างหนี้
                    $NationalCreditBureau->Percent_Payment = $this->PercentPayment();
                    $NationalCreditBureau->Type_Of_Credit_Card = null; //ระบุประเภทบัตรเครดิต (สำหรับบัตรเครดิต) ตามตารางประเภทบัตร (Appendix A)
                    $NationalCreditBureau->Number_Of_Co_Borrower = $this->NumberOfCoBorrower($Number = 0);
                    $NationalCreditBureau->Unit_Make = $this->UnitMakeAndUnitModel($DataContract->APP_ID);
                    $NationalCreditBureau->Unit_Model = $this->UnitMakeAndUnitModel($DataContract->APP_ID);
                    $NationalCreditBureau->Credit_Limit_Type_Flag = $this->CreditLimitTypeFlag();
                    $NationalCreditBureau->Lot_Ref = $guId;
                    if ($NationalCreditBureau->save()) {
                        echo " Save Success !!";
                        $do = $do + 1;
                    } else {
                        echo " Error !!";
                    }
                    // }

                } else {
                    echo " | Data not found!!";
                }

                if ($do == 100) {
                    $Contract = false;
                }

                echo "\n";
            }

            $page++;
        } while (count($Contract));

        // if($is_pass){
        //     NBC_WORK_QUEUE::where('id', $checkQueue["ID"])->update(array(
        //             'STATUS' => 4,
        //             'IN_PROGRESS' => 0,
        //             'update_at' => date('Y-m-d H:i:s'),
        //             'update_by' => 148
        //         )
        //     );
        // }

    }


    // เช็คว่าต้องข้อความต้องเป็นอังกฤษเท่านั้น ถึงจะ
    private function LanguageEnAndSetUpper($str)
    {

        $strtoupper = '';

        if (preg_match('/^(.*)[a-z0-9](.*)+$/i', $str)) {
            // ทำให้เป็นตัวใหญ่
            $strtoupper =  strtoupper($str);
        } else {
            $strtoupper =  $str;
        }

        $result = str_replace(" ", "", $strtoupper);

        return $str;
    }

    private function IdTypeNCB($type_card, $Nationality)
    {
        $results = '';
        // ระบุค่าประเภท ID ดังนี้
        // 01 = Citizen ID
        if ($Nationality == 1) {
            if ($type_card == 1) {
                $results = '01';
            } else if ($type_card == 2) {
                $results = '07';
            } else if ($type_card == 3) {
                $results = '02';
            }
        } else {
            // 02 = Civil Servant ID (Government) คนไทยให้ ส่ง 01 เท่านั้น
            // 05 = Alien-บัตรประจำตัวคนซึ่งไม่มีสัญญาไทย (เลขบัตรขึ้นต้นด้วย 6 กับ 7)
            // 07 = Foreign Passport/ID (เลขที่ Passport ของคนต่างชาติ)
            // 09 = Other (เลขบัตรขึ้นต้นด้วย 0 บัตรประจำตัวบุคคลที่ไม่มีสถานะทางทะเบียน)
        }

        return $results;
    }

    private function NumberOfDaysPastDueDelinquencyStatus($days)
    {
        //, $Credit_limit = 0 , $Outstanding = 0 
        // ระบุจำนวนวันค้างชำระตาม Delay Payment Code (นับจากลูกค้าผิดนัดชำระจนถึง ณ งวดสินเดือนที่ส่งข้อมูล แล้วนำมา Mapping กับตาราง)
        // การนำส่งจำนวนวันค้างชำระ (DPD) ระบบบุคคลธรรมดา
        $set_value = '';
        if ($days <= 30) {
            // ไม่ค้างชำระหรือค้างชำระไม่เกิน 30 วัน
            $set_value = "000";
        } else if (($days >= 31) && ($days <= 60)) {

            $set_value = "001";
        } else if (($days >= 61) && ($days <= 90)) {

            $set_value = "002";
        } else if (($days >= 91) && ($days <= 120)) {

            $set_value = "003";
        } else if (($days >= 121) && ($days <= 150)) {

            $set_value = "004";
        } else if (($days >= 151) && ($days <= 180)) {

            $set_value = "005";
        } else if (($days >= 181) && ($days <= 210)) {

            $set_value = "006";
        } else if (($days >= 211) && ($days <= 240)) {

            $set_value = "007";
        } else if (($days >= 241) && ($days <= 270)) {

            $set_value = "008";
        } else if (($days >= 271) && ($days <= 300)) {

            $set_value = "009";
        } else if ($days >= 301) {

            $set_value = "F";
        }
        //  else if ($Outstanding > $Credit_limit) {
        // ยอดค้างชำระ > วงเงินสินเชื่อลูกค้าไม่ได้ชำระเงินตามวงเงินเครดิตคงเหลือหรือ ลูกค้าไม่ชำระเงิน (เกินกำหนดชำระ)
        // Outstanding > Credit limit Customer did not pay to balance credit limit or customer did not pay (Overdue Limit)
        // } else if ($Outstanding < $Credit_limit) {
        // Outstanding < Credit limit Customer made payment to balance credit limit or customer pay (Within limit)
        // ยอดคงค้าง < วงเงินสินเชื่อ ลูกค้าชำระเงินยอดคงเหลือวงเงินสินเชื่อหรือลูกค้าชำระเงิน (ภายในวงเงิน)
        // }

        return  $set_value;
    }

    private function InstallmentFrequency($value)
    {

        // ระบุความถี่ในการชำระตามค่าที่กำหนด
        // 0 = Unspecifiedม , 1 = Weekly , 2 = Biweekly
        // 3 = Monthly , 4 = Bimonthly , 5 = Quarterly
        // 6 = Daily , 7 = Special use (lump sum etc.)
        // 8 = Semi-yearly , 9 = Yearly

        $set_value =  0;
        switch ($value) {
                // 1 = Weekly
            case 1:
                $set_value = 1;
                break;
                // 2 = Biweekly
            case 2:
                $set_value = 2;
                break;
                // 3 = Monthly
            case 3:
                $set_value = 3;
                break;
                // 4 = Bimonthly
            case 4:
                $set_value = 4;
                break;
                // 5 = Quarterly
            case 5:
                $set_value = 5;
                break;
                // 6 = Daily
            case 6:
                $set_value = 6;
                // 7 = Special use (lump sum etc.)
            case 7:
                $set_value = 7;
                // 8 = Semi-yearly
            case 8:
                $set_value = 8;
                // 9 = Yearly
            case 9:
                $set_value = 9;
                break;
                // 0 = Unspecified
            default:
                $set_value = 0;
        }
        return  $set_value;
    }

    private function NumberOfDaysPastDue($APP_ID)
    {
        $data = $AmountPastDue =  array();
        $dateNow = date("Y-m-d");
        $Amount_Past_Due_Status = false;
        $TOTAL_INSTALL_AMT = 0;
        $CreditLimitOriginalLoanAmount = 0;
        $Installment_Amount = 0; /// เก็บจำนวนเงินทีผิดนัดชำระ
        $Default_Date = '19000101';
        $DateOfLastPayment = '';
        $date_now = date('Y-m-d', strtotime("-1 month"));

        /// สร้างต่อเดือน เช็คว่าเดือนที่สรุปเป็นหนี้ ไหม ถ้าเป็นให้เช็คย้อนหลังว่าเป็นกี่เดือนย้อนหลังกี่เดือน 
        $CreditLimitOriginalLoanAmount = CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->sum("INSTALL_AMT");
        $CUSTOMER_CARD =  CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->where('DUEDATE', 'like', date("Y-m", strtotime($date_now)) . '%')->get();


        foreach ($CUSTOMER_CARD as $value) {
            $Installment_Amount = $value->INSTALL_AMT;

            // $carbonDate = Carbon::createFromFormat('M j Y h:i:s:A', $value->DUEDATE);
            $carbonDate = $this->ConvertFormateDate($value->DUEDATE);
            if ($carbonDate->format('Y-m') <= date("Y-m", strtotime($date_now))) {
                // เช็คว่า invoice ต้องไม่เท่ากับ null , 1 ;
                if (($value->INVOICE_NUMBER != NULL || $value->INVOICE_NUMBER != "NULL" || $value->INVOICE_NUMBER != "1")) {
                    // เช็คว่ามีการค้างชำระ
                    if (($value->RECEIPT_NUMBER == NULL || $value->RECEIPT_NUMBER == "NULL")) {
                        if (!$Amount_Past_Due_Status) {
                            $Default_Date = $carbonDate->format('Ymd');
                            $Amount_Past_Due_Status = true;
                        }
                    }
                }
            } else {
                // ไม่มีการค้างชำระ

            }
        }

        $REPAY_DATE =  REPAYMENT::WHERE('APP_ID', $APP_ID)->max('REPAY_DATE');
        // $DateOfLastPayment = Carbon::createFromFormat('M j Y h:i:s:A', $REPAY_DATE);

        $DateOfLastPayment = ($this->ConvertFormateDate($REPAY_DATE))->format('Ymd');

        $AmountPastDue =  $this->AmountPastDue($APP_ID, $date_now);

        if ($Amount_Past_Due_Status) {
            // มีการค้างชำระ
            $Default_Date = $AmountPastDue["Default_Date"];
            $AmountPastDue = $AmountPastDue["DPD"]; //จำนวนวันที่ผิดชำระ
            $DateOfLastPayment  =  $AmountPastDue["DateOfLastPayment"]; //เก็บค่าที่ชำระเงินล่าสุด
            $Days = $this->DPDandDays($AmountPastDue["DPD"]);
        } else {
            $Days = '000';
            $Default_Date;
            $DateOfLastPayment;
            $Default_Date = $AmountPastDue["Default_Date"];
            $AmountPastDue = $AmountPastDue["DPD"]; //จำนวนวันที่ผิดชำระ
        }

        return  array(
            "Default_Date" => $Default_Date,
            "Days" => $Days,
            "InstallmentAmount" => $Installment_Amount,
            "AmountPastDue" => $AmountPastDue,
            "StatusAmountPastDue" => $Amount_Past_Due_Status,
            "DateOfLastPayment" => $DateOfLastPayment,
        );
    }

    private function LoanObjective($value)
    {

        $set_value =  0;
        switch ($value) {
                // 44444 สำหรับบัญชีคลินิกแก้หนี้
            case '44444':
                $set_value = '44444';
                break;
                // 55555 สำหรับบัญชีสินเชื่อส่วนบุคคลภายใต้การกำกับ (ปว.58)
            case '55555':
                $set_value = '55555';
                break;
                // 66666 สำหรับบัญชีสินเชื่อเพื่อผู้สูงอายุโดยมีที่อยู่อาศัยเป็นหลักประกัน (Reverse Mortgage)
            case '66666':
                $set_value = '66666';
                break;
                // 77777 สำหรับบัญชีสินเชื่อพิโกไฟแนนซ์ (PicoFinance)
            case '77777':
                $set_value = '77777';
                break;
                // 88888 สำหรับสินเชื่อดิจิทัล (Digital PersonalLoan)
            case '88888':
                $set_value = '88888';
                break;
                // 55554 โอนขายหนี้บางส่วน
            default:
                $set_value = '55554';
        }
        return  $set_value;
        // ระบุวัตถุประสงค์การกู้ โดยระบุรหัสวัตุประสงค์การกู้
        // 44444 สำหรับบัญชีคลินิกแก้หนี้
        // 55555 สำหรับบัญชีสินเชื่อส่วนบุคคลภายใต้การกำกับ (ปว.58)
        // 66666 สำหรับบัญชีสินเชื่อเพื่อผู้สูงอายุโดยมีที่อยู่อาศัยเป็นหลักประกัน (Reverse Mortgage)
        // 77777 สำหรับบัญชีสินเชื่อพิโกไฟแนนซ์ (PicoFinance)
        // 88888 สำหรับสินเชื่อดิจิทัล (Digital PersonalLoan)
        // 55554 โอนขายหนี้บางส่วน
    }

    private function DateOfLastDebtRestructuring($days, $Restructuring)
    {
        // กรณีบัญชีค้างชำระเกิน 90 วัน และมีการปรับปรุงโครงสร้างหนี้ให้รายงานวันที่ปรับปรุงโครงสร้างหนี้
        // หมายเหตุ: กรณียังไม่มีการปรับปรุงโครงสร้างหนี้ สมาชิกนำส่งค่า 19000101 (ไม่มีข้อมูล ระบบจะไม่แสดงผล) ระบบจะ Update ข้อมูล Field ดังกล่าวตามที่สมาชิกนำส่งทุกครั้ง
        if ($days > 90) {
            if ($Restructuring) {
                // วันที่ปรับปรุงโครงสร้างหนี้
            } else {
                return '19000101';
            }
        } else {
            return date('Ymd');
        }
    }

    private function AccountStatus($status, $days, $month, $year, $AmountPastDue, $customer_type = '01')
    {
        // *********** ลูกค้าประเภทบุคคนธรรมดา ยังไม่มีในนามนิติบุคคล ทุกระบบ *********** //
        // *********** เลย กำหนดให้ $customer_type = 01              *********** //
        // *********** $AmountPastDue = true มีการค้างชำระหนี้ Overdud  *********** //

        $status_covid19 = false;
        $set_value = '';

        if ($customer_type == '01') {
            if ($status == 40 || $status == 53) {
                // ลูกค้าประเภทบุคคนธรรมดา 11 ลูกหนี้ชำระหนี้หมด หรือชำระครบตามยอกที่ได้ตกลงประนอมหนี้ระหว่างสมาชิกและลูกค้า เดือนถัดไป หยุดนำส่งข้อมูล
                $set_value = '11';
            }

            // ลูกค้าประเภทบุคคนธรรมดา 12 พักชำระหนี้ตามนโยบายชองสมาชิก
            // ลูกค้าประเภทบุคคนธรรมดา 13 พักชำระหนี้ตามนโยบายของรัฐ
            // ลูกค้าประเภทบุคคนธรรมดา 14 พักชำระหนี้เกษตรกรตามนโยบายของรัฐ

            if ($status == 46 || $status == 47 || $status == 54) {
                // ลูกค้าประเภทบุคคนธรรมดา 30 อยู่ในกระบวนการทางกฏหมาย
                // ลูกค้าประเภทบุคคนธรรมดา 31 อยู่ในระหว่างชำระหนี้ตามคำพิพากษาตามยอม
                // ลูกค้าประเภทบุคคนธรรมดา 32 ศาลยกฟ้อง
                // ลูกค้าประเภทบุคคนธรรมดา 33 ปิดบัญชีเนื่องจากตัดหนี้สูญ และสมาชิกตัดหนี้สูญทั้งหมด โดยไม่ติดใจทวงถามอีก
                // ลูกค้าประเภทบุคคนธรรมดา 40 อยู่ระหว่างชำระสินเชื่อปิดบัญชี เมือลูกค้าชำระหนี้เสร็จสิ้นจะต้องปรับเป็น 11 หรือ 011
                // ลูกค้าประเภทบุคคนธรรมดา 41 อยู่ระหว่างตรวจสอบรายากร
                // ลูกค้าประเภทบุคคนธรรมดา 43 โอนหรือขายหนี้ และชำระหนี้เสร็จสิ้น
                // ลูกค้าประเภทบุคคนธรรมดา 44 โอนหรือขายหนี้ ที่เป็นสถานะบัญชีปกติ
            }

            if ($year >= 2019 && $year <= 2024) {
                /// ช่วงโควิต และหยุด มาตาการต่างๆของโควิด
                $status_covid19 = true;
                if ($year == 2024 && ($month >= 4)) {
                    $status_covid19 = false;
                }
            }

            if ($AmountPastDue === true) {
                if ($days < 90 ) {
                    // ลูกค้าประเภทบุคคนธรรมดา 10 ไม่มีหนี้ค้างชำระหรือมีหนี้ค้างชำระไม่เกิน 90 วัน
                    $set_value = '10';
                } else if ($days >= 90) {
                    if ($status == 56) {
                        // ลูกค้าประเภทบุคคนธรรมดา 42 โอนหรือขายหนี้ที่ค้างชำระเกิน 90 วัน
                        $set_value = '42';
                    } else if (($status_covid19 === false) && (($status >= 43 && $status <= 48) || $status == 54)) {
                        // ลูกค้าประเภทบุคคนธรรมดา 20 หนี้ค้างชำระเกิน 90 วัน
                        $set_value = '20';
                    } else if ($status_covid19 === true) {
                        // ลูกค้าประเภทบุคคนธรรมดา 21 หนี้ค้างชำระเกิน 90 วันเนื่องจากรับผลกระทบจากสถานการณ์ที่ไม่ปกติ
                        $set_value = '21';
                    }
                }
            }else{
                // ไม่มีหนี้ค้างชำระหรือมีหนี้ค้างชำระไม่เกิน 90 วัน
                $set_value = '10';
            }
        }
        // else if ($customer_type == '02') {
        //     /// doing 
        //     // ลูกค้าประเภทนิติบุคคล 010 ไม่มีหนี้ค้างชำระหรือมีหนี้ค้างชำระไม่เกิน 90 วัน
        //     // ลูกค้าประเภทนิติบุคคล 011 ลูกหนี้ชำระหนี้หมด หรือชำระครบตามยอกที่ได้ตกลงประนอมหนี้ระหว่างสมาชิกและลูกค้า
        //     // ลูกค้าประเภทนิติบุคคล 012 พักชำระหนี้ตามนโยบายชองสมาชิก
        //     // ลูกค้าประเภทนิติบุคคล 013 พักชำระหนี้ตามนโยบายของรัฐ
        //     // ลูกค้าประเภทนิติบุคคล 014 พักชำระหนี้เกษตรกรตามนโยบายของรัฐ
        //     // ลูกค้าประเภทนิติบุคคล 020 หนี้ค้างชำระเกิน 90 วัน
        //     // ลูกค้าประเภทนิติบุคคล 021 หนี้ค้างชำระเกิน 90 วันเนื่องจากรับผลกระทบจากสถานการณ์ที่ไม่ปกติ
        //     // ลูกค้าประเภทนิติบุคคล 030 อยู่ในกระบวนการทางกฏหมาย
        //     // ลูกค้าประเภทนิติบุคคล 031 อยู่ในระหว่างชำระหนี้ตามคำพิพากษาตามยอม
        //     // ลูกค้าประเภทนิติบุคคล 032 ศาลยกฟ้อง
        //     // ลูกค้าประเภทนิติบุคคล 033 ปิดบัญชีเนื่องจากตัดหนี้สูญ และสมาชิกตัดหนี้สูญทั้งหมด โดยไม่ติดใจทวงถามอีก
        //     // ลูกค้าประเภทนิติบุคคล 040 อยู่ระหว่างชำระสินเชื่อปิดบัญชี เมือลูกค้าชำระหนี้เสร็จสิ้นจะต้องปรับเป็น 11 หรือ 011
        //     // ลูกค้าประเภทนิติบุคคล 041 อยู่ระหว่างตรวจสอบรายากร
        //     // ลูกค้าประเภทนิติบุคคล 042 โอนหรือขายหนี้ที่ค้างชำระเกิน 90 วันไปให้บุคคลอื่น
        //     // ลูกค้าประเภทนิติบุคคล 043 โอนหรือขายหนี้ และชำระหนี้เสร็จสิ้น และลูกหนี้ชำระหนี้ให้แก้ผู่รับโอน แล้ว
        //     // ลูกค้าประเภทนิติบุคคล 044 โอนหรือขายหนี้ ที่เป็นสถานะบัญชีปกติ ไม่ค้างหรือ ค้างชำระไม่เกิน 90 วันไปให้คนอื่น
        // }

        return $set_value;
    }

    // private function DPDandDays($days , $Credit_limit = 0 , $Outstanding = 0 )
    // {
    //     // การนำส่งจำนวนวันค้างชำระ (DPD) ระบบบุคคลธรรมดา
    //     $set_value = '';
    //     if ($days <= 30) {
    //         // ไม่ค้างชำระหรือค้างชำระไม่เกิน 30 วัน
    //         $set_value = "000";
    //     } else if (($days >= 31) && ($days <= 60)) {

    //         $set_value = "001";
    //     } else if (($days >= 61) && ($days <= 90)) {

    //         $set_value = "002";
    //     } else if (($days >= 91) && ($days <= 120)) {

    //         $set_value = "003";
    //     } else if (($days >= 121) && ($days <= 150)) {

    //         $set_value = "004";
    //     } else if (($days >= 151) && ($days <= 180)) {

    //         $set_value = "005";
    //     } else if (($days >= 181) && ($days <= 210)) {

    //         $set_value = "006";
    //     } else if (($days >= 211) && ($days <= 240)) {

    //         $set_value = "007";
    //     } else if (($days >= 241) && ($days <= 270)) {

    //         $set_value = "008";
    //     } else if (($days >= 271) && ($days <= 300)) {

    //         $set_value = "009";
    //     } else if ($days >= 301) {

    //         $set_value = "__F";
    //     } else if ($Outstanding > $Credit_limit) {
    //         // ยอดค้างชำระ > วงเงินสินเชื่อลูกค้าไม่ได้ชำระเงินตามวงเงินเครดิตคงเหลือหรือ ลูกค้าไม่ชำระเงิน (เกินกำหนดชำระ)
    //         // Outstanding > Credit limit Customer did not pay to balance credit limit or customer did not pay (Overdue Limit)
    //     } else if ($Outstanding < $Credit_limit) {
    //         // Outstanding < Credit limit Customer made payment to balance credit limit or customer pay (Within limit)
    //         // ยอดคงค้าง < วงเงินสินเชื่อ ลูกค้าชำระเงินยอดคงเหลือวงเงินสินเชื่อหรือลูกค้าชำระเงิน (ภายในวงเงิน)
    //     }

    //     return  $set_value;
    // }  

    private function CreditLimitTypeFlag()
    {
        // ระบุรหัสตามที่ NCB กำหนดแต่ละ สมาชิก ให้นำส่งกรณีบัญชีมีการใช้วงเงินร่วมมากกว่า 1 บัญชี ในลูกหนี้นั้นๆ หรือบัตรเครดิตที่ใช้วงเงินร่วมกัน
        return  $set_value = 99;
    }

    private function NumberOfCoBorrower($Number)
    {
        // ระบุจำนวนผู้กู้ร่วม กรณีกู้เดี่ยวระบุเป็น 00 กรณี Ownership เป็น กู้ร่วมให้ระบุจำนวนผู้กู้ร่วม
        if ($Number) {
            $set_value = str_pad($Number, 2, "0", STR_PAD_LEFT);
        } else {
            $set_value = '00';
        }
        return  $set_value;
    }

    private function PercentPayment()
    {
        // ระบุเปอร์เซนต์ขั้นตำที่ชำระ (สำหรับบัตรเครดิต / สินเชื่อบุคคลมีการการชำระตามขั้นต่ำ/อื่นๆ)
        $PercentPayment = 3;
        return $PercentPayment;
    }

    private function SetFromFormatNCB($date)
    {
        if ($date) {
            // $carbonDate = Carbon::createFromFormat('M j Y h:i:s:A', $date);
            $carbonDate = $this->ConvertFormateDate($date);
            return $carbonDate->format('Ymd');
        } else {
            return '19000101';
        }
    }

    private function AmountPastDue($APP_ID, $date_now)
    {

        $first = false;
        $pay_last = false;
        $results = array();
        $Amount_Past_Due = 0;
        $DateOfLastPayment = '19000101';
        $Default_Date = '19000101';
        // $CUSTOMER_CARD =  CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->where('DUEDATE','<=', date("Y-m-d", strtotime($date_now)))->orderBy('INSTALL_NUM', 'DESC')->get();
        // REPAYMENT จะเกิดก็ต่อเมื่อ มีการสร้าง invoice 
        $REPAYMENT = REPAYMENT::WHERE('APP_ID', $APP_ID)->WHERE('REPAY_TYPE', 2)->orderby('INSTALL', 'DESC')->get();

        // foreach ( $CUSTOMER_CARD as $value ) {
        /// APP_ID  = '100048' and  REPAY_TYPE = 2 order by INSTALL DESC
        foreach ($REPAYMENT as $value) {

            // $carbonDate = Carbon::createFromFormat('M j Y h:i:s:A', $value->PAY_DATE);
            $carbonDate = $this->ConvertFormateDate($value->PAY_DATE);

            if (($value->INVOICE_NUMBER != NULL || $value->INVOICE_NUMBER != "NULL" || $value->INVOICE_NUMBER == "1")) {
                // เช็คว่ามีการค้างชำระ
                if (($value->RECEIPT_NUMBER == NULL || $value->RECEIPT_NUMBER == "NULL")) {
                    if (!$first) {
                        $first = true;
                    }
                    $Default_Date = $carbonDate->format('Ymd');
                    $Date_End = date("Y-m-d", strtotime($carbonDate));
                    $Amount_Past_Due_Status = true;
                } else {
                    // ไม่มีการค้างชำระ หรือมีการชำระ
                    if (!$pay_last) {
                        // เก็บค่าที่ชำระเงินล่าสุด
                        $DateOfLastPayment = date("Ymd", strtotime($carbonDate));
                        $pay_last = true;
                    }
                    $Date_End = date("Y-m-d", strtotime($value->REPAY_DATE));
                    $Amount_Past_Due = 0;
                    $Amount_Past_Due_Status = false;
                }
            }
        }

        if ($Amount_Past_Due_Status) {
            $Amount_Past_Due =  CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->whereNull("RECEIPT_NUMBER")->where('DUEDATE', '<=', date("Y-m-d", strtotime($date_now)))->orderBy('INSTALL_NUM', 'DESC')->sum("INSTALL_AMT");
        }

        return  $results = array(
            "DPD" => round((strtotime($date_now) - strtotime($Date_End)) / (60 * 60 * 24)),
            "Default_Date" =>  $Default_Date,
            "DateOfLastPayment" => $DateOfLastPayment,
            "AmountPastDue" => round($Amount_Past_Due),
            "AmountPastDueStatus" => $Amount_Past_Due_Status
        );
    }

    private function SetNewAddress($PERSON_ID)
    {

        $Address = '';
        $Address_sql = ADDRESS::WHERE('PERSON_ID', $PERSON_ID)->first();

        $Address .=  (($Address_sql["A1_NO"] == "") || ($Address_sql["A1_NO"] == '-') || ($Address_sql["A1_NO"]  == 'ไม่มี') || ($Address_sql["A1_NO"]  == '_')) ? ""  : "บ้านเลขที่ " . $Address_sql["A1_NO"];
        $Address .=  (($Address_sql["A1_MOI"] == "") || ($Address_sql["A1_MOI"] == '-') || ($Address_sql["A1_MOI"] == 'ไม่มี') || ($Address_sql["A1_MOI"] == '_')) ? "" : " หมู่ที่ " . $Address_sql["A1_MOI"];
        $Address .=  (($Address_sql["A1_VILLAGE"] == "") || ($Address_sql["A1_VILLAGE"] == '-') || ($Address_sql["A1_VILLAGE"] == 'ไม่มี') || ($Address_sql["A1_VILLAGE"] == '_')) ? "" : " หมู่บ้าน " . $Address_sql["A1_VILLAGE"];
        $Address .=  (($Address_sql["A1_BUILDING"] == "") || ($Address_sql["A1_BUILDING"] == '-') || ($Address_sql["A1_BUILDING"] == 'ไม่มี') || ($Address_sql["A1_BUILDING"] == '_')) ? "" :  " ตึก " . $Address_sql["A1_BUILDING"];
        $Address .=  (($Address_sql["A1_FLOOR"] == "") || ($Address_sql["A1_FLOOR"] == '-') || ($Address_sql["A1_FLOOR"] == 'ไม่มี') || ($Address_sql["A1_FLOOR"] == '_')) ? ""  : " ชั้น " . $Address_sql["A1_FLOOR"];
        $Address .=  (($Address_sql["A1_ROOM_NO"] == "") || ($Address_sql["A1_ROOM_NO"] == '-') || ($Address_sql["A1_ROOM_NO"] == 'ไม่มี') || ($Address_sql["A1_ROOM_NO"] == '_')) ? "" : " ห้อง " . $Address_sql["A1_ROOM_NO"];
        $Address .=  (($Address_sql["A1_SOI"] == "") || ($Address_sql["A1_SOI"] == '-') || ($Address_sql["A1_SOI"] == 'ไม่มี') || ($Address_sql["A1_SOI"] == '_')) ? "" : " ซอย " . $Address_sql["A1_SOI"];
        $Address .=  (($Address_sql["A1_ROAD"] == "") || ($Address_sql["A1_ROAD"] == '-') || ($Address_sql["A1_ROAD"] == 'ไม่มี') || ($Address_sql["A1_ROAD"] == '_')) ? "" :  " ถนน" . $Address_sql["A1_ROAD"];

        $Province = MT_PROVINCE::WHERE('PROVINCE_ID', $Address_sql["A1_PROVINCE"])->pluck("PROVINCE_NAME")->first();
        $District = MT_DISTRICT::WHERE('PROVINCE_ID', $Address_sql["A1_PROVINCE"])->WHERE('DISTRICT_ID', $Address_sql["A1_DISTRICT"])->pluck("DISTRICT_NAME")->first();
        $SubDistrict = MT_SUB_DISTRICT::WHERE('DISTRICT_ID', $Address_sql["A1_DISTRICT"])->WHERE('SUB_DISTRICT_ID', $Address_sql["A1_SUBDISTRICT"])->pluck("SUB_DISTRICT_NAME")->first();

        return array(
            "Address_1" => mb_substr($Address, 0, 50, 'UTF-8'),
            "Address_2" => mb_substr($Address, 50, 50, 'UTF-8'),
            "Address_3" => mb_substr($Address, 100, 50, 'UTF-8'),
            "Province" => $Province,
            "District" => $District,
            "SubDistrict" => $SubDistrict,
            "PostalCode" => $Address_sql["A1_POSTALCODE"],
        );
    }

    private function CreditLimitOriginalLoanAmount($APP_ID)
    {

        ///วงเงินที่ได้รับอนุมัติตามสัญญาไม่มีจุดทศนิยม
        /// แบบไม่ คิดดอกแล้ว ภาษี
        // $CreditLimitOriginalLoanAmount = CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->sum("PAY_PRINCIPLE");
        // return round($CreditLimitOriginalLoanAmount);
        /// ยอดหนี้ทั้งหมด
        $CreditLimitOriginalLoanAmount = PRODUCT::WHERE('APP_ID', $APP_ID)->sum("HP_VAT_SUM");

        return round($CreditLimitOriginalLoanAmount);
    }

    private function InstallmentAmount($APP_ID)
    {
        /// ยอดผ่อนชำระ/ยอดชำระในแต่ละงวด
        /// ให้รายงานสำหรับสินเชื่อที่มีการผ่อนชำระ รายงานจำนวนเงินหน่วยเป็นบาท ไม่มีจุดทศนิยม
        /// แบบไม่ คิดดอกแล้ว ภาษี
        // $InstallmentAmount = CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->pluck("PAY_PRINCIPLE")->toArray();
        // print_r($InstallmentAmount);
        // return round($InstallmentAmount);
        $InstallmentAmount = CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->pluck("INSTALL_AMT")->toArray();
        return round($InstallmentAmount['0']);
    }

    private function CurrentNewMemberCode()
    {
        $results = 0;
        // ระบุค่ารหัสสมาชิกผู้จัดส่งข้อมูล ซึ่งกำหนดโดย NCB
        return $results = $this->member_data['membercode'];
    }

    private function CurrentNewMemberName()
    {
        $results = 0;
        // ระบุค่าชื่อย่อของสมาชิก ซึ่งกำหนดโดย NCB
        return $results = $this->member_data['membername'];
    }

    private function CurrentNewAccountNumber()
    {
        $results = 0;
        // ระบุค่าหมายเลขบัญชีสินเชื่อของลูกหนี้
        return $results;
    }

    private function UnitMakeAndUnitModel($APP_ID)
    {
        $results = 0;
        //ระบุยี่ห้อของสินค้า Required สำหรับสินเชื่อให้เช่าแบบลิสซิ่ง หรือเช่าซื้อ
        // ระบุรุ่นของสินค้า Required สำหรับสินเชื่อให้เช่าแบบลิสซิ่ง หรือเช่าซื้อ

        $UnitMakeAndUnitModel = PRODUCT::WHERE('APP_ID', $APP_ID)->pluck("MODEL_NAME")->toArray();

        

        return substr($UnitMakeAndUnitModel['0'], 0, 15);
    }

    private function Person($APP_ID)
    {

        $Marital_Status = '';
        $sex = 0;

        $persan = PERSON::WHERE('APP_ID', $APP_ID)->first();

        if ($persan["MARITAL_STATUS"] == 1) {
            $Marital_Status = '0002';
        } else if ($persan["MARITAL_STATUS"] == 2) {
            $Marital_Status = '0001';
        } else if ($persan["MARITAL_STATUS"] == 4) {
            $Marital_Status = '0003';
        } else if ($persan["MARITAL_STATUS"] == 5) {
            $Marital_Status = '0004';
        } else {
            $Marital_Status = '0000';
        }

        //// sex
        if ($persan["SEX"] == 1) {
            $sex = 2;
        } else {
            $sex = 1;
        }

        //// BirthDay
        // if($persan["BIRTHDAY"]){
        //     $BirthDay = Carbon::createFromFormat('M j Y h:i:s:A', $persan["BIRTHDAY"]);
        //     $BirthDay =  $BirthDay->format('Ymd');
        // }
        $BirthDay = ($this->ConvertFormateDate($persan["BIRTHDAY"]))->format('Ymd');

        //// PREFIX
        if ($persan["PREFIX"] <= 3) {
            $Prefix = MT_PREFIX::WHERE('Prefix_ID', $persan["PREFIX"])->pluck("Prefix_name")->first();
        } else {
            $Prefix = $persan["PREFIX_OTHER"];
        }

        return  array(
            "Person_ID" => $persan["PERSON_ID"],
            "Family_Name_1" => $persan["LAST_NAME"],
            "Family_Name_2" => $persan["LAST_NAME"],
            "First_Name" => $persan["FIRST_NAME"],
            "Card_code"  => $persan["CARD_CODE"],
            "Nationality_Code" => $persan["NATIONALITY_CODE"],
            "Sex" => $sex,
            "Marital_Status" => $Marital_Status,
            "Phone" =>  $persan["PHONE"],
            "BirthDay" => $BirthDay,
            "TAX_ID" => $persan["TAX_ID"],
            "Prefix" => $Prefix,
            "Nationality" => ($persan["NATIONALITY_CODE"]  == 1) ? "01" : NULL,
        );
    }

    private function AmountOwedCreditUse($APP_ID)
    {

        // ยอดหนี้คงเหลือ/ยอดวงเงินที่ใช้ไป
        // ให้รายงานยอดหนี้คงเหลือ/ยอดวงเงินที่ใช้ไป
        // ให้นำส่งดอกเบี้ยตามแต่ละประเภทสินเชื่อตามประกาศ กคค.
        // กรณีค่าติดลบให้รานงานเป็น 0 (ศูนย์) รายงานจำนวนเงินหน่วยเป็นบาท ไม่มีจุดทศนิยม

        /// ยอดหนี้คงเหลือทั้งหมด
        $AmountOwedCreditUse = CUSTOMER_CARD::WHERE('APPLICATION_NUMBER', $APP_ID)->whereNull("RECEIPT_NUMBER")->sum("INSTALL_AMT");
        return  round($AmountOwedCreditUse);
    }

    public function checkQueue()
    {

        $code = '0';
        $is_pass = true;
        $queuePullWork = null;
        $Lot_ref = "";
        $ID = null;
        $IN_PROGRESS = 0;
        // เช็คว่า queue อยู่ในสถานะที่จะทำงานไหม ถ้ามีสถานะอยู่ใน 2 = รอทำงาน | 3 = กำลังทำงาน จะไม่ให้สร้างคิวใหม่
        $queuePullWork = NBC_WORK_QUEUE::WHERE("STATUS", 2)->orderBy('ID', 'ASC')->first();
        if ($queuePullWork == null) {
            $is_pass = false;
            echo 'Queue not found';
        }

        /// เช็ควันที่และเวลาใน ฟิว DATE_WORK ว่าถ้่าถึงเวลาที่ต้องทำงานรึยัง
        if ($is_pass) {
            if (date('Y-m-d H:i', strtotime($queuePullWork->DATE_WORK)) == date('Y-m-d H:i')) {
                $ID = $queuePullWork->ID;
                $Lot_ref .= $queuePullWork->LOT_REF;
                $queuePullWork->STATUS = 3;
                $queuePullWork->IN_PROGRESS = 1;
                $queuePullWork->update();
            } else {
                // echo "Not work";
                $is_pass = false;
            }
        }

        if (!$is_pass) {
            $code = '1';
        }

        return array(
            "Code" => $code,
            "ID" => $ID,
            "Lot_ref" => $Lot_ref
        );
    }


    function ConvertFormateDate($date)
    {
        $ConvertDate = '';
        if ($date) {
            if (strpos($date, '-') !== false) {
                // Date is in "YYYY-MM-DD" format
                $ConvertDate = Carbon::createFromFormat('Y-m-d', $date);
            } else {
                // Date is in "M j Y h:i:s:A" format
                $ConvertDate = Carbon::createFromFormat('M j Y h:i:s:A', $date);
            }
        }

        return $ConvertDate;
    }
}
