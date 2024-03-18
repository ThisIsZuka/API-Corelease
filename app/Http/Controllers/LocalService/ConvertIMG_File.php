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

class ConvertIMG_File extends BaseController
{
    public function ConvertFile()
    {
        $Get_tag_K2 = new Get_tag_K2();
        // $array = explode("\r\n", file_get_contents(public_path('list.txt')));
        $file = fopen(public_path('storage_local/list.txt'), 'r');

        // Read the first line
        $line = fgets($file);
        // dd($line);
        $name_folder = "TestConvertIMG";
        if ($line !== false) {
            // Process the line (You can replace this with your processing logic)
            // $this->info("Processing line: $line");
            $DB_IMAGE_FILE = DB::table('IMAGE_FILE')->where('APP_ID', (int)$line)->first();
            // dd($DB_IMAGE_FILE);
            $Get_tag_K2->GetImageCardID($DB_IMAGE_FILE->APP_ID, "ID_CARD_$DB_IMAGE_FILE->APP_ID", $name_folder);

            // Remove the line from the file
            file_put_contents(public_path('storage_local/list.txt'), str_replace($line, '', file_get_contents(public_path('storage_local/list.txt'))));
        } else {
            $this->info('No more lines to process.');
        }

        // Close the file
        fclose($file);
    }
}
