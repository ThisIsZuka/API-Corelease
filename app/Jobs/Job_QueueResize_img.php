<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Image;
use \Gumlet\ImageResize;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class Job_QueueResize_img implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $DB_Data;

    public function __construct($DB_Data)
    {
        $this->DB_Data = $DB_Data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $DB_Data = $this->DB_Data;
        $IMAGE_FILE = DB::table('dbo.IMAGE_FILE')
            ->select('IMAGE_ID', 'CARD_CODE_FILE', 'STUDENT_CARD_FILE', 'FACE_PERSON', 'PRODUCT_SERIAL', 'CUSTOMER_DELIVER', 'RETURN_ASSETS02')
            ->where('IMAGE_ID' , $DB_Data)
            ->first();
        dd($IMAGE_FILE);
        $this->GetData($DB_Data);
        sleep(1);
    }


    public function GetData($_Data)
    {
        try {
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

                if ($_Data->$key != null && $_Data->$key != '') {
                    // dd($value['function']);

                    $img64 = $this->get_string_between($_Data->$key, '<content>', '</content>');
                    $img_name = $this->get_string_between($_Data->$key, '<name>', '</name>');

                    $funcname = $value['function'];
                    $re_new = $this->$funcname($img64);


                    $Img_New_Size = "<file><name>" . $img_name . "</name><content>" . $re_new . "</content></file>";

                    DB::table('dbo.IMAGE_FILE')
                        ->where('IMAGE_ID', $_Data->IMAGE_ID)
                        ->update([
                            $key => $Img_New_Size,
                        ]);
                }
            }

            DB::table('dbo.IMAGE_FILE')
                ->where('IMAGE_ID', $_Data->IMAGE_ID)
                ->update([
                    'RETURN_ASSETS02' => '1'
                ]);
                
        } catch (Exception $e) {
            // bird is clearly not the word
            $this->failed($e, $_Data->IMAGE_ID);
        }
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
    {
        $size_in_bytes = (int) (strlen($base64Image));
        // $size_in_kb    = $size_in_bytes / 1024;
        // $size_in_mb    = $size_in_kb / 1024;

        return $size_in_bytes;
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

    public function failed($exception, $IMAGE_ID)
    {
        // $exception->getMessage();
        // etc...
        $log = new Logger('Error');
        $log->pushHandler(new RotatingFileHandler(storage_path() . '/logs/ResizeImage/resize_.log', 2, Logger::INFO));

        $log->info('Image ID :' . $IMAGE_ID . '[ Error' . $exception->getMessage() . ']');
    }
}
