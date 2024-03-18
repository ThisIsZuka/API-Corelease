<?php

namespace App\Http\Controllers\LocalService;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\LocalService\Get_tag_K2;
use Illuminate\Support\Facades\Log;

class ConvertPDF_File extends BaseController
{

    public function Con_PDF()
    {

        $Get_tag_K2 = new Get_tag_K2();

        // $content = File::get(public_path('list.txt'));
        $array = explode("\r\n", file_get_contents(public_path('list.txt')));

        $perPage = 10;
        $totalRecords = count($array);

        $totalPages = ceil($totalRecords / $perPage);
        
        for ($page = 0; $page < $totalPages; $page++) {
            $offset = $page * $perPage;
            
            $list = array_slice($array, $offset, $perPage);
            // print_r($list);
            // print_r(substr($list, 2)) ;
            $count_list = count($list);
            for ($i=0; $i < $count_list; $i++) { 
            //    echo  $list[$i];
               echo $list[$i]; 
               echo "==";
               echo substr($list[$i], 2);
               //select("CONTRACT_ID,CARD_CODE_FILE,STUDENT_CARD_FILE,FACE_PERSON,CUSTOMER_DELIVER")->
               $DB_PDF_FORM = DB::table('IMAGE_FILE')->where('CONTRACT_ID',substr($list[$i], 2))->get();

               echo "==";
               if($DB_PDF_FORM){
                    echo "มีข้อมูล";
               }else{
                echo "ไม่มีข้อมูล";
               }

               echo "==";

               foreach ($DB_PDF_FORM as $value) {

                $name_folder = "ContractNumber_".$list[$i];
                $Get_tag_K2->GetImageCardID($value->APP_ID, "ID_CARD_$value->CONTRACT_ID", $name_folder);
                $Get_tag_K2->GetImageCusDeliver($value->APP_ID, "CUS_DELIVER_$value->CONTRACT_ID", $name_folder);
                $Get_tag_K2->GetImageStudentCard($value->APP_ID, "STUDENT_CARD_$value->CONTRACT_ID", $name_folder);
                $Get_tag_K2->GetImageFacePerson($value->APP_ID, "FACE_PERSON_$value->CONTRACT_ID", $name_folder);

                Log::channel('running')->info("Contract : $value->APP_ID");
                echo "สร้างสำเร็จ";
               }

               echo "<BR>";

            }

            // $DB_PDF_FORM = DB::table('PDF_FORM')
            //     ->select('CONTRACT.CONTRACT_NUMBER', 'CONTRACT.CUSTOMER_NAME', 'PDF_FORM.*')
            //     ->leftJoin('CONTRACT', 'PDF_FORM.CONTRACT_ID', '=', 'CONTRACT.CONTRACT_ID')
            //     ->where('PDF_TYPE', 'CONTRACT')
            //     ->whereIn('CONTRACT.CONTRACT_NUMBER', $list)
            //     // ->orderBy('CONTRACT.CONTRACT_NUMBER')
            //     ->get();

           // $DB_PDF_FORM = DB::table('IMAGE_FILE')->select('CONTRACT_ID,CARD_CODE_FILE,STUDENT_CARD_FILE,FACE_PERSON,CUSTOMER_DELIVER')->whereIn('CONTRACT_ID',substr($list, 2))->get();
            // foreach ($DB_PDF_FORM as $value) {
            //     // $base64_PDF = $Convert_tag_K2->GET_base64_FromTag($value->PDF_NAME);
            //     // $filename = 'CONTRACT_' . $value->CONTRACT_NUMBER . '.pdf';
            //     $name_folder = "ContractNumber_$value->CONTRACT_ID";

            //     // $Convert_tag_K2->ConvertToTMPFile($base64_PDF, $filename, $name_folder);
            //     $Convert_tag_K2->GetImageCardID($value->APP_ID, "ID_CARD_$value->CONTRACT_ID", $name_folder);
            //     $Convert_tag_K2->GetImageCusDeliver($value->APP_ID, "CUS_DELIVER_$value->CONTRACT_ID", $name_folder);

            //     echo "Contract : $value->CONTRACT_NUMBER";
            //     echo "\n";
            //     Log::channel('running')->info("Contract : $value->CONTRACT_NUMBER");
            // }
        }


        return true;
    }
}
