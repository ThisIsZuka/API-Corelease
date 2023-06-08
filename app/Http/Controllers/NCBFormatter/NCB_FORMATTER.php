<?php

namespace App\Http\Controllers\NCBFormatter;

use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Session;

use function PHPUnit\Framework\isEmpty;

/**
 * กรณีที่มีการผิดนัดชำระหนี้ แต่มีการชำระภายหลังแม้ไม่เต็มจำนวน จะไม่ต้องทำการส่งรายงานวันค้างชำระแล้ว
 * การผิดนัดชำระจะนับเฉพาะกรณีที่ผิดนัดชำระมากกว่า 1 ครั้งเท่านั้น
 * 
 * ส่วนยอดค้าางชำระจะอ้างอิงจากยอดค้างชำระตามจริง
 */

class NCB_FORMATTER {
    public const TUDF = "TUDF";
    public const TRAILER = "TRLR";

    public function __construct()
    {
        $this->production = true;
        $this->date = new DateTime('now');
        $month = $this->date->modify('-1 month')->format('m');
        $this->filename = "TUCRS-THUNDERF-{$this->date->modify('-1 month')->format('Y')}-{$month}";
        $this->pathfile = "";
        $this->version = "";
        $this->type = "";
        $this->header_section = "";
        $this->body_section = "";
        $this->result = "";
        $this->member_data = [
            "membercode" => env("NCB_MEMBER_CODE"),
            "membername" => env("NCB_MEMBER_NAME"),
            "password" => env("NCB_MEMBER_PASSWORD"),
            "futureuse" => env("NCB_FUTURE_USE"),
            "memberdata" => env("NCB_MEMBER_DATA")
        ];
    }

    function getData($date = '') {
        try {
            $db = DB::connection('k2prd');
            $str_where = '';
            // $this->raw = $db->select($db->raw('EXEC dbo.SP_NCB_GETINSTALLDETAIL_DEMO'));
            if ($date !== '') {
                $str_where = 'WHERE [AS OF DATE] = ' . "'$date'" . ' AND [FAMILY NAME] IS NOT NULL';
            }
            $this->raw = $db->select($db->raw('
            SELECT
            [FAMILY NAME] COLLATE THAI_CI_AS [FAMILY NAME]
            ,[FIRST NAME] COLLATE THAI_CI_AS [FIRST NAME]
            ,[DATE OF BIRTH]
            ,[CUSTOMER TYPE]
            ,[ID TYPE]
            ,[ID NUMBER]
            ,[ISSUE COUNTRY]
            ,[ADDRESS LINE 1] COLLATE THAI_CI_AS [ADDRESS LINE 1]
            ,[SUB DISTINCT] COLLATE THAI_CI_AS [SUB DISTINCT]
            ,[DISTINCT] COLLATE THAI_CI_AS [DISTINCT]
            ,[PROVINCE] COLLATE THAI_CI_AS [PROVINCE]
            ,[COUNTRY]
            ,[POSTAL CODE]
            ,[ACCOUNT NUMBER]
            ,[ACCOUNT TYPE]
            ,[OWNERSHIP INDICATOR]
            ,[CURRENCY CODE]
            ,[DATE ACCOUNT OPENED]
            ,[DATE OF LAST PAYMENT]
            ,[DATE ACCOUNT CLOSE]
            ,[AS OF DATE]
            ,[CREDIT LIMIT]
            ,[AMOUNT OWNED]
            ,[AMOUNT PAST DUE]
            ,[NUMBER OF DAY PAST DUE]
            ,[DEFAULT DATE]
            ,[INSTALLMENT FREQUECY]
            ,[INSTALLMENT AMOUNT]
            ,[INSTALLMENT NUMBER OF PAYMENT]
            ,[ACCOUNT STATUS]
            ,[DATE OF LAST DEBT RESTRUCTURING]
            FROM NCB_testFormat
            ' . $str_where));
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getFormatter() {
        $this->txtfile = "";
        $this->txtfile = $this->header_text_file() . "\r\n";
        $this->txtfile .= $this->body_text_file();
        $this->txtfile .= NCB_FORMATTER::TRAILER;

        return $this;
    }

    function getData_with_head() {

    }

    function header_text_file() {
        $this->section = 'header';
        $head = NCB_FORMATTER::TUDF;
        $date = new DateTime();
        
        $head .= $this->version;
        $head .= $this->chk_requirement('membercode');
        $head .= $this->chk_requirement('membername');
        $head .= $this->chk_requirement('cycle_identification');
        $head .= $this->chk_requirement('as_of_date', $date->modify('-1 month')->modify('last day of this month')->format('Ymd'));
        $head .= $this->chk_requirement('password');
        $head .= $this->chk_requirement('futureuse');
        $head .= $this->chk_requirement('memberdata');
        $head .= $this->chk_requirement('tracing_number', count($this->raw));

        return $head;
    }
    
    function body_text_file() {
        $this->section = "body";
        $body = "";

        for ($x = 0;$x < count($this->raw);$x++) {
            $data = $this->raw[$x];
            for ($y = 0;$y < count(array_keys($this->tudf_body_section));$y++) {
                $segmentName = array_keys($this->tudf_body_section)[$y];
                for ($z = 0;$z < count($this->tudf_body_section[$segmentName]);$z++) {
                    $fieldname = array_keys($this->tudf_body_section[$segmentName])[$z];
                    $value = isset($data->$fieldname) ? $data->$fieldname:'';
                    $body .= $this->chk_requirement($fieldname, $value, $segmentName, $x);
                }
            }
            $body .= "\r\n";
        }

        return $body;
    }

    function non_whiteSpaceClear($str, $subst = '') {
        $re = '/[\x{200B}-\x{200D}\x{FEFF}\x{FD3E}\x{FD3F}\x{00A7}\x{002D}]/u';
        $result = preg_replace($re, $subst, $str);

        return $result;
    }

    private function chk_requirement($fieldname, $value = '', $secmentname = '', $row = '') {
        $txt = "";
        $zerofill = false;
        $freespace = false;
        if ($this->section == 'header') {
            $fieldtag = "";
            $str = isset($this->member_data[$fieldname]) ? $this->member_data[$fieldname]:$value;
            $txtlength = mb_strlen($str, 'utf-8');
            $required = true;
            $requestCountStringLength = isset($this->tudf_header_section[$fieldname]["countStringLenght"]) ? $this->tudf_header_section[$fieldname]["countStringLenght"]:false;
            $fixedLength = isset($this->tudf_header_section[$fieldname]["fixedLength"]) ? $this->tudf_header_section[$fieldname]["fixedLength"]:0;
            $zerofill = isset($this->tudf_header_section[$fieldname]["zerofill"]) ? $this->tudf_header_section[$fieldname]["zerofill"]:false;
            $freespace = isset($this->tudf_header_section[$fieldname]["freespace"]) ? $this->tudf_header_section[$fieldname]["freespace"]:false;
            $maxLength = 0;
            // $uppercase = false;
            $this->position = isset($this->tudf_header_section[$fieldname]["position"]) ? $this->tudf_header_section[$fieldname]["position"]:'prefix';
        } else {
            $fieldtag = isset($this->tudf_body_section[$secmentname][$fieldname]["FieldTag"])? $this->tudf_body_section[$secmentname][$fieldname]["FieldTag"]:'';
            $raw = (array) $this->raw[$row];
            $str = isset($raw[strtoupper($fieldname)]) ? $this->non_whiteSpaceClear($raw[strtoupper($fieldname)]):$this->non_whiteSpaceClear($value);
            $required = isset($this->tudf_body_section[$secmentname][$fieldname]["required"]) ? $this->tudf_body_section[$secmentname][$fieldname]["required"]:true;
            $options = isset($this->tudf_body_section[$secmentname][$fieldname]["options"]) ? $this->tudf_body_section[$secmentname][$fieldname]["options"]:[];
            $default = isset($this->tudf_body_section[$secmentname][$fieldname]["default"]) ? $this->tudf_body_section[$secmentname][$fieldname]["default"]:'';
            $str = $str == ''||$str == null ? $default:$str;
            // if (count($options) > 0) {
            //     $str = $options[$str];
            // }
            $txtlength = mb_strlen($str, 'utf-8');
            $requestCountStringLength = isset($this->tudf_body_section[$secmentname][$fieldname]["countStringLenght"]) ? $this->tudf_body_section[$secmentname][$fieldname]["countStringLenght"]:false;
            $fixedLength = isset($this->tudf_body_section[$secmentname][$fieldname]["fixedLength"]) ? $this->tudf_body_section[$secmentname][$fieldname]["fixedLength"]:0;
            $maxLength = isset($this->tudf_body_section[$secmentname][$fieldname]["maxLength"]) ? $this->tudf_body_section[$secmentname][$fieldname]["maxLength"]:0;
            $zerofill = isset($this->tudf_body_section[$secmentname][$fieldname]["zerofill"]) ? $this->tudf_body_section[$secmentname][$fieldname]["zerofill"]:false;
            $freespace = isset($this->tudf_body_section[$secmentname][$fieldname]["freespace"]) ? $this->tudf_body_section[$secmentname][$fieldname]["freespace"]:false;
            $this->position = isset($this->tudf_body_section[$secmentname][$fieldname]["position"]) ? $this->tudf_body_section[$secmentname][$fieldname]["position"]:'prefix';
            $uppercase = true;
        }

        
        if ($fixedLength > 0) {
            // UTF-8 ใช้ 3 bytes ใน 1 char ใช้ strlen ไม่ได้ต้องใช้ mb_strlen แทน
            $txtlength = mb_strlen($str, 'utf-8');

            $str = mb_substr($str, 0,  $fixedLength);
            $txt .= $zerofill ? $this->zerofill(strtoupper($str), $fixedLength - $txtlength):'';
            $txt .= $freespace ? $this->freespace(strtoupper($str), $fixedLength - $txtlength):'';
            $txt .= !$zerofill && !$freespace ? strtoupper($str):'';
        } else if ($maxLength > 0) {
            $str = mb_substr($str, 0, $maxLength);
            $txt .= mb_substr($str, 0, $maxLength);
        } else {
            $txt .= strtoupper($str);
        }

        $txtlength = mb_strlen($txt, 'utf-8');

        if ($txtlength == 0&&!$required) {
            return  '';
        } else {
            $pre = $requestCountStringLength ? $this->prefix()->zerofill($txtlength, (2 - mb_strlen($txtlength, 'utf-8')) < 0?0:(2 - mb_strlen($txtlength, 'utf-8'))):'';
            return  $fieldtag . $pre . $txt;
        }
    }

    private function prefix() {
        $this->position = 'prefix';
        return $this;
    }

    private function postfix() {
        $this->position = 'postfix';
        return $this;
    }

    private function freespace($txt, $length) {
        if ($this->position == 'postfix') {
            $str = $txt . $this->repeat(' ', $length);
        } else {
            $str = $this->repeat(' ', $length) . $txt;
        }

        return $str;
    }

    private function zerofill($txt, $length) {
        if ($this->position == 'postfix') {
            $str = $txt . $this->repeat('0', $length);
        } else {
            $str = $this->repeat('0', $length) . $txt;
        }

        return $str;
    }

    private function repeat($string, $number) {
        return str_repeat($string, $number);
    }

    // function generate_by_type() {
    //     if (!is_dir($this->pathfile)) {
    //         $this->make($this->pathfile);
    //     }
    // }
}