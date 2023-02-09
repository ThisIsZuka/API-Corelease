<?php

namespace App\Http\Controllers\helper;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\CustomException;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Convert_tag_K2 extends BaseController
{

    public function GET_base64_FromTag($TagBase64)
    {

        try{
            preg_match("/(?<=<content>)(.*)(?=<\/content>)/", $TagBase64, $matches);
            return $matches[0];

        }catch(Exception $e){
            dd($e);
        }
    }


    public function GET_fileName_FromTag($TagBase64)
    {

        try{
            preg_match("/(?<=<name>)(.*)(?=<\/name>)/g", $TagBase64, $matches);
            return $matches[0];

        }catch(Exception $e){
            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('dmYHis');
            return 'file_temp_'.$date_now.'.pdf';
        }
    }


    public function ConvertToTMPFile($base64String , $fileName = 'file.pdf')
    {
        // Decode the Base64 string to binary data
        $binaryData = base64_decode($base64String);

        // Create a temporary file from the binary data
        $tempFile = tmpfile();
        fwrite($tempFile, $binaryData);

        // Create an UploadedFile object from the temporary file
        $uploadedFile = new UploadedFile(stream_get_meta_data($tempFile)['uri'], $fileName, 'application/pdf', null, true);

        Storage::disk('public')->put($fileName, file_get_contents($uploadedFile));

        // return $uploadedFile;
    }

}
