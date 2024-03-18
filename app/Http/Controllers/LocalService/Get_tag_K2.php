<?php

namespace App\Http\Controllers\LocalService;

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

class Get_tag_K2 extends BaseController
{

    public function GET_base64_FromTag($TagBase64)
    {
        try {
            preg_match("/(?<=<content>)(.*)(?=<\/content>)/", $TagBase64, $matches);
            if (empty($matches) || !isset($matches[0])) {
                error_log("No base64 string found in the tag");
                return null;
            }
            return $matches[0];
        } catch (Exception $e) {
            error_log("Error in GET_base64_FromTag: " . $e->getMessage());
            return null;
        }
    }


    public function GET_fileName_FromTag($TagBase64)
    {

        try {
            preg_match("/(?<=<name>)(.*)(?=<\/name>)/g", $TagBase64, $matches);
            return $matches[0];
        } catch (Exception $e) {
            $date_now = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('dmYHis');
            return 'file_temp_' . $date_now . '.pdf';
        }
    }


    public function ConvertToTMPFile($base64String, $fileName = 'file.pdf', $name_folder)
    {
        try {
            // Decode the Base64 string to binary data
            $binaryData = base64_decode($base64String);

            // Create a temporary file from the binary data
            $tempFile = tmpfile();
            fwrite($tempFile, $binaryData);

            // Create an UploadedFile object from the temporary file
            $uploadedFile = new UploadedFile(stream_get_meta_data($tempFile)['uri'], $fileName, 'application/pdf', null, true);

            Storage::disk('public')->put('Contract/' . $name_folder . '/' . $fileName, file_get_contents($uploadedFile));
            // Storage::disk('public')->put($fileName, file_get_contents($uploadedFile));

            return file_get_contents($uploadedFile);
        } catch (Exception $e) {
            // var_dump($e);
        }
    }

    //// CARD_CODE_FILE
    public function GetImageCardID($APP_ID, $fileName = 'id_card.jpg', $name_folder)
    {
        $IMAGE_FILE = DB::table('IMAGE_FILE')
            ->select('CARD_CODE_FILE')
            ->where('APP_ID', $APP_ID)
            // ->where('CONTRACT_ID', $APP_ID)
            ->first();

        if (is_null($IMAGE_FILE) || is_null($IMAGE_FILE->CARD_CODE_FILE)) {
            error_log("No image file found for APP_ID: $APP_ID");
            return 0;
        }

        $base64String = $this->GET_base64_FromTag($IMAGE_FILE->CARD_CODE_FILE);

        if (is_null($base64String)) {
            error_log("Failed to extract base64 string for APP_ID: $APP_ID");
            return 0;
        }

        $Extension = $this->getExtensionFromTag($IMAGE_FILE->CARD_CODE_FILE);

        $this->ConvertBase64ToImg($base64String, $name_folder, $fileName, $Extension);
    }

    /// CUSTOMER_DELIVER
    public function GetImageCusDeliver($APP_ID, $fileName = 'customer_deliver.jpg', $name_folder)
    {
        $IMAGE_FILE = DB::table('IMAGE_FILE')
            ->select('CUSTOMER_DELIVER')
            ->where('APP_ID', $APP_ID)
            // ->where('CONTRACT_ID', $APP_ID)
            ->first();

        if (is_null($IMAGE_FILE) || is_null($IMAGE_FILE->CUSTOMER_DELIVER)) {
            error_log("No image file found for APP_ID: $APP_ID");
            return 0;
        }

        $base64String = $this->GET_base64_FromTag($IMAGE_FILE->CUSTOMER_DELIVER);

        if (is_null($base64String)) {
            error_log("Failed to extract base64 string for APP_ID: $APP_ID");
            return 0;
        }

        $Extension = $this->getExtensionFromTag($IMAGE_FILE->CUSTOMER_DELIVER);

        $this->ConvertBase64ToImg($base64String, $name_folder, $fileName, $Extension);
    }

    //// STUDENT_CARD_FILE
    public function GetImageStudentCard($APP_ID, $fileName = 'student_card.jpg', $name_folder)
    {
        $IMAGE_FILE = DB::table('IMAGE_FILE')
            ->select('STUDENT_CARD_FILE')
            ->where('APP_ID', $APP_ID)
            // ->where('CONTRACT_ID', $APP_ID)
            ->first();

        if (is_null($IMAGE_FILE) || is_null($IMAGE_FILE->STUDENT_CARD_FILE)) {
            error_log("No image file found for APP_ID: $APP_ID");
            return 0;
        }

        $base64String = $this->GET_base64_FromTag($IMAGE_FILE->STUDENT_CARD_FILE);

        if (is_null($base64String)) {
            error_log("Failed to extract base64 string for APP_ID: $APP_ID");
            return 0;
        }

        $Extension = $this->getExtensionFromTag($IMAGE_FILE->STUDENT_CARD_FILE);

        $this->ConvertBase64ToImg($base64String, $name_folder, $fileName, $Extension);
    }
    
    //// FACE_PERSON
    public function GetImageFacePerson($APP_ID, $fileName = 'face_person.jpg', $name_folder)
    {
        $IMAGE_FILE = DB::table('IMAGE_FILE')
            ->select('FACE_PERSON')
            ->where('APP_ID', $APP_ID)
            // ->where('CONTRACT_ID', $APP_ID)
            ->first();

        if (is_null($IMAGE_FILE) || is_null($IMAGE_FILE->FACE_PERSON)) {
            error_log("No image file found for APP_ID: $APP_ID");
            return 0;
        }

        $base64String = $this->GET_base64_FromTag($IMAGE_FILE->FACE_PERSON);

        if (is_null($base64String)) {
            error_log("Failed to extract base64 string for APP_ID: $APP_ID");
            return 0;
        }

        $Extension = $this->getExtensionFromTag($IMAGE_FILE->FACE_PERSON);

        $this->ConvertBase64ToImg($base64String, $name_folder, $fileName, $Extension);
    }

    public function ConvertBase64ToImg($base64String, $name_folder, $fileName, $Extension)
    {
        try {
            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64String));

            if ($imageData === false) {
                throw new Exception('Invalid base64 data');
            }

            // Use a default extension for testing
            $NameSave = "$fileName.$Extension";

            // Save the image
            Storage::disk('public')->put('Contract/' . $name_folder . '/' . $NameSave, $imageData);
        } catch (Exception $e) {
            // var_dump($e);
        }
    }

    function getExtensionFromTag($TagBase64) {
        preg_match("/<name>(.*\.(jpg|jpeg|png|gif|bmp))<\/name>/i", $TagBase64, $matches);
        if (!empty($matches) && isset($matches[1])) {
            // Extract the file extension
            $filename = $matches[1];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            return $extension;
        } else {
            return "jpg";
        }
    }
    
}
