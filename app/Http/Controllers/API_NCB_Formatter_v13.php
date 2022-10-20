<?php

namespace App\Http\Controllers;

use App\Http\Controllers\files\file;
use App\Http\Controllers\NCBFormatter\NCB_FORMATTER;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;

class API_NCB_FORMATTER_v13 extends NCB_FORMATTER {
    public function __construct()
    {
        parent::__construct();
        $this->version = '13';
        $this->pathfile = '/file_location/report_ncb/' . $this->version;
        $this->file = new file($this->pathfile);

        $this->tudf_header_section = [
            'version' => [
                "fixedLength" => 2
            ],
            'membercode' => [
                "freespace" => true,
                "position" => 'postfix',
                "fixedLength" => 10
            ],
            'membername' => [
                "freespace" => true,
                "position" => 'postfix',
                "fixedLength" => 16
            ],
            'cycle_identification' => [
                "freespace" => true,
                "fixedLength" => 2
            ],
            'as_of_date' => [
                "fixedLength" => 8
            ],
            'password' => [
                "fixedLength" => 8
            ],
            'futureuse' => [
                "zerofill" => true,
                "position" => 'prefix',
                "fixedLength" => 2
            ],
            'memberdata' => [
                "freespace" => true,
                "position" => 'prefix',
                "fixedLength" => 40
            ],
            'tracing_number' => [
                "zerofill" => true,
                "position" => 'prefix',
                "fixedLength" => 8
            ]
        ];

        $this->getData();
    }
    public function getReport($filetype = '') {
        if ($filetype != '') {
            $this->getData()->generate($filetype);
        }

        return $this;
    }

    public function generate($filetype = 'txt', $encrypt = true) {
        // $file = '';
        // if ($filetype == 'txt') {
            $this->getFormatter();
        // } else {
        //     $this->getFormatter();
        //     $encrypt = false;
        // }

        // if ($encrypt) {
        //     $this->encrypt();
        // }

        return $this;
    }

    public function encrypt($encrypt = 'rar') {

    }

    public function toString() {
        return ["filename" => $this->filename, "data" => $this->getData_with_head()];
    }
}