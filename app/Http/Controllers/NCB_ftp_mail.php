<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use File;

use Illuminate\Support\Facades\Mail;
use App\Mail\NCB_Mail;

class NCB_ftp_mail extends BaseController
{
    public function SendFile($file)
    {
        $file_name = $file;
        $file_content = public_path($file);
        // dd(fopen($file_content, 'r+'));
        try {

            Storage::disk('ftp_ncb')->put($file_name, fopen($file_content, 'r+'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function SendMail($zip_pass)
    {
        Mail::to('kittisak.u@comseven.com')->send(new NCB_Mail($zip_pass));
    }
}
