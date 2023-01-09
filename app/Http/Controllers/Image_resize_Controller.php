<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;
use Illuminate\Support\Facades\File;

use App\Image;
use \Gumlet\ImageResize;

use Illuminate\Support\Facades\Http;

use App\Jobs\Job_QueueResize_img;

class Image_resize_Controller extends BaseController
{

    private function is_image($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];

        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            return true;
        }
        return false;
    }



    public function ResizeImage_smalles($image)
    {

        // $img_resize = $image;

        if ($this->getBase64ImageSize($image) >= 1000000) {

            $img_resize = $this->getBase64ImageSize($image);
            $new_img_resize = $img_resize;
            $num_count = 0;
            while ($new_img_resize > 2000000) {
                if ($new_img_resize < 1000000) {
                    $num_count = 0.8;
                    break;
                }
                $num_count += 0.1;
                $new_img_resize = $img_resize;
                // var_dump($new_img_resize . " = " . $new_img_resize . " - " . " ( " . $this->getBase64ImageSize($image) * $num_count . ")");
                // echo "<br>";
                $new_img_resize = $new_img_resize - ($this->getBase64ImageSize($image)  * $num_count);
                // var_dump($num_count);

                if ($num_count > 0.70) {
                    $num_count = 0.70;
                    break;
                }
            }
            $num_count = $num_count * 100;
            // dd($num_count);
            $image_resize = $image;
            // var_dump($this->getBase64ImageSize($image_resize));
            $num_count =  100 - $num_count;
            // dd($image_resize);
            $image_resize = ImageResize::createFromString(base64_decode($image));
            // var_dump($this->getBase64ImageSize($image_resize));
            $image_resize->scale($num_count);
            $image_resize->getImageAsString();
            $image_resize = base64_encode($image_resize);
        } else {
            $image_resize = $image;
        }
        // dd($image_resize);
        return $image_resize;
        // dd($image_resize);
    }


    public function ResizeImage_30percen($image)
    {
        if ($this->getBase64ImageSize($image) >= 4000000) {

            $image_resize = ImageResize::createFromString(base64_decode($image));

            $image_resize->getImageAsString();
            $image_resize = base64_encode($image_resize);
        } else {
            $image_resize = $image;
        }
        // dd($image_resize);
        return $image_resize;
    }


    public function getBase64ImageSize($base64Image)
    { //return memory size in B, KB, MB
        try {
            // $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_bytes = (int) (strlen($base64Image));
            // $size_in_kb    = $size_in_bytes / 1024;
            // $size_in_mb    = $size_in_kb / 1024;

            return $size_in_bytes;
        } catch (Exception $e) {
            return $e;
        }
    }


    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public function GetImage_base64()
    {

        try {

            $IMAGE_FILE = DB::table('dbo.IMAGE_FILE')
                ->select('IMAGE_ID', 'CARD_CODE_FILE', 'STUDENT_CARD_FILE', 'FACE_PERSON', 'PRODUCT_SERIAL', 'CUSTOMER_DELIVER', 'RETURN_ASSETS02')
                // ->where('RETURN_ASSETS02', '!=' ,'1')
                // ->limit(10)
                // ->where('IMAGE_ID', '71482')
                ->get();

            // dd($IMAGE_FILE);

            foreach ($IMAGE_FILE as $item) {
                // dd($item->IMAGE_ID);
                $obj = [
                    'CARD_CODE_FILE' => [
                        'function' => 'ResizeImage_smalles',
                    ],
                    'STUDENT_CARD_FILE' => [
                        'function' => 'ResizeImage_30percen',
                    ],
                    'FACE_PERSON' => [
                        'function' => 'ResizeImage_smalles',
                    ],
                    'CUSTOMER_DELIVER' => [
                        'function' => 'ResizeImage_smalles',
                    ]
                ];

                foreach ($obj as $key => $value) {
                    if ($item->$key != null && $item->$key != '') {
                        // dd($value['function']);
                        // dd($key);

                        $img64 = $this->get_string_between($item->$key, '<content>', '</content>');
                        $img_name = $this->get_string_between($item->$key, '<name>', '</name>');

                        $funcname = $value['function'];
                        $re_new = $this->$funcname($img64);

                        // $img_name = preg_replace('/\S+/', '', $img_name);
                        $img_name = str_replace(' ', '', $img_name);
                        // dd($img_name);


                        $Img_New_Size = "<file><name>" . $img_name . "</name><content>" . $re_new . "</content></file>";
                        // dd($Img_New_Size);

                        DB::table('dbo.IMAGE_FILE')
                            ->where('IMAGE_ID', $item->IMAGE_ID)
                            ->update([
                                $key => $Img_New_Size,
                                // 'RETURN_ASSETS02' => '1'
                            ]);
                    }
                }

                // DB::table('dbo.IMAGE_FILE')
                //     ->where('IMAGE_ID', $item->IMAGE_ID)
                //     ->update([
                //         'RETURN_ASSETS02' => '1'
                //     ]);
            }


            return 'Success';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function Job_Resize()
    {

        $return_data = new \stdClass();
        $IMAGE_FILE = DB::table('dbo.IMAGE_FILE')
            // ->select('IMAGE_ID', 'CARD_CODE_FILE', 'STUDENT_CARD_FILE', 'FACE_PERSON', 'PRODUCT_SERIAL', 'CUSTOMER_DELIVER', 'RETURN_ASSETS02')
            ->select('IMAGE_ID')
            // ->whereNull('RETURN_ASSETS02')
            // ->limit(2)
            ->get();


        foreach ($IMAGE_FILE as $item) {
            // dd($item);
            Job_QueueResize_img::dispatch($item);
        }

        $return_data->Code = '999999';
        $return_data->Status = 'Images resize Processing';

        return $return_data;
    }

    public function rate_limit_test()
    {
        for($i = 0 ; $i < 1; $i++){
            // $response = Http::withHeaders()->post('https://uat.ufundportal.com/API-Corelease/api/master_university');
            $response = Http::withHeaders([
                'content-type' => 'application/json',
            ])->post('https://uat.ufundportal.com/API-Corelease/api/master_university', [
            ]);
            var_dump($i);
        }
        
    }
}
