<?php
namespace App\Http\Controllers;

use App\Http\Controllers\files\file;
use App\Http\Controllers\NCBFormatter\NCB_FORMATTER;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Session;

class API_NCB_FORMATTER_v13 extends NCB_FORMATTER {
    public function __construct()
    {
        parent::__construct();
        require(dirname(__DIR__) . '/Controllers/NCBv13Configuration/config.v13.php');
        // var_dump($NCBV13['header'], $NCBV13['body']);
        set_time_limit(0);
        $this->version = '13';
        $this->pathfile = '/file_location/report_ncb/' . $this->version;
        $this->file = new file($this->pathfile);
        $this->file->check_folder_is_exsist($this->pathfile, 'public');
        $this->tudf_header_section = $NCBV13['header'];
        $this->tudf_body_section = $NCBV13['body'];

        $this->getData();
    }
    public function getReport($filetype = '') {
        if ($filetype != '') {
            $this->getData()->generate($filetype);
        }

        return $this;
    }

    public function show(){
        // $header = array_keys($this->raw[0]);
        var_dump($this->getData()->generate()->txtfile);
        // return view('show_txtfile', ['header' => $header, 'rawdata' => $this->raw]);
    }

    public function getfiles() {
        $file = scandir('E:/phpProject/API-Corelease/public/file_location/report_ncb/13/');
        return response()->json($file);
    }

    public function generate($date, $filetype = 'txt', $encrypt = true) {
        $this->getData(str_replace('-', '', $date));

        $file = '';
        $dir = '';
        if ($filetype == 'txt') {
            $this->getFormatter();

            $dir = $this->file
            ->setType($filetype)
            ->setContent($this->txtfile)
            ->getFile($this->filename);

            return $dir;
        } else {
            $this->getFormatter();
            $encrypt = false;
        }

        return $this;
    }

    public function encrypt($encrypt = 'rar') {

    }

    public function toString() {
        return ["filename" => $this->filename, "data" => $this->getData_with_head()];
    }
}

