<?php

namespace App\Http\Controllers\NCBFormatter;

use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Session;

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
        $this->date = new DateTime('now');
        $this->filename = "ncb-{$this->date->format('Ymd')}";
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

    function getData() {
        try {
            $this->raw = DB::select(DB::raw('EXEC dbo.SP_REPORT_NCB_VERSION_DEMO'));
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getFormatter() {
        $this->txtfile = $this->header_text_file();
        $this->txtfile .= NCB_FORMATTER::TRAILER;

        return $this;
    }

    function getData_with_head() {

    }

    function header_text_file() {
        $this->section = 'header';
        $head = NCB_FORMATTER::TUDF;
        
        $head .= $this->version;
        $head .= $this->chk_requirement('membercode');
        $head .= $this->chk_requirement('membername');
        $head .= $this->chk_requirement('cycle_identification');
        $head .= $this->chk_requirement('as_of_date', $this->date->format('Ymd'));
        $head .= $this->chk_requirement('password');
        $head .= $this->chk_requirement('futureuse');
        $head .= $this->chk_requirement('memberdata');
        $head .= $this->chk_requirement('tracing_number', count($this->raw));

        return $head;
    }

    private function chk_requirement($fieldname, $value = '') {
        $txt = "";
        $zerofill = false;
        $freespace = false;
        if ($this->section == 'header') {
            $str = isset($this->member_data[$fieldname]) ? $this->member_data[$fieldname]:$value;
            $txtlength = strlen($str);
            $fixedLength = isset($this->tudf_header_section[$fieldname]["fixedLength"]) ? $this->tudf_header_section[$fieldname]["fixedLength"]:0;
            $zerofill = isset($this->tudf_header_section[$fieldname]["zerofill"]) ? $this->tudf_header_section[$fieldname]["zerofill"]:false;
            $freespace = isset($this->tudf_header_section[$fieldname]["freespace"]) ? $this->tudf_header_section[$fieldname]["freespace"]:false;
            $this->position = isset($this->tudf_header_section[$fieldname]["position"]) ? $this->tudf_header_section[$fieldname]["position"]:'prefix';
        } else {

        }

        if ($fixedLength > 0) {
            if ($zerofill) {
                $txt = $this->zerofill(strtoupper($str), $fixedLength - $txtlength);
            } else if ($freespace) {
                $txt = $this->freespace(strtoupper($str), $fixedLength - $txtlength);
            } else {
                $txt = strtoupper($str);
            }
        } else {
            $txt = strtoupper($str);
        }
        
        return $txt;
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
            $str = $txt . $this->repeat('&nbsp;', $length);
        } else {
            $str = $this->repeat('&nbsp;', $length) . $txt;
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

    function body_text_file() {
        return $this;
    }

    // function generate_by_type() {
    //     if (!is_dir($this->pathfile)) {
    //         $this->make($this->pathfile);
    //     }
    // }
}