<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use File;
use ZipArchive;
use Illuminate\Support\Facades\App;

use App\Http\Controllers\NCB_ftp_mail;


class NCB_ZipFile extends BaseController
{
    public function Zipfile($req_file = 'xample.json', $req_password = '1234')
    {
        // $req_file = 'xample.json';
        // $req_password = '1234';

        $public_dir = public_path();

        $zip = new ZipArchive;

        $files = public_path($req_file);
        $file_name = $this->Get_file_name($req_file);
        $file_content = File::get(public_path($req_file));

        $zipFileName = $this->Get_fileZip_name($file_name);

        $res = $zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE); //Add your file name
        if ($res === TRUE) {
            $zip->addFromString($file_name, $file_content); //Add your file name
            $zip->setEncryptionName($file_name, ZipArchive::EM_AES_256, $req_password); //Add file name and password dynamically
            $zip->close();

            $NCB_ftp_mail = new NCB_ftp_mail;
            $NCB_ftp_mail->SendFile($zipFileName);
            $NCB_ftp_mail->SendMail($req_password);

            echo 'ok';
        } else {
            echo 'failed';
        }
    }

    function Get_file_name($files)
    {
        $arr_file_name = preg_split("/\\\\/", public_path($files), -1, PREG_SPLIT_NO_EMPTY);
        // dd($arr_file_name);
        $file_name = end($arr_file_name);

        return $file_name;
    }


    function Get_fileZip_name($filesName)
    {
        $arr_file_name = explode(".", $filesName);
        $file_zip = $arr_file_name[0];
        // dd($file_zip);
        return $file_zip.'.zip';
    }
}
