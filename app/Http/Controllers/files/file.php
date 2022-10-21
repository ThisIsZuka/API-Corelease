<?php 

namespace App\Http\Controllers\files;

use File as fs;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Session;

class file {
    public CONST PERMISSION = [
        "public" => '0777',
        "private" => '0644'
    ];

    function __construct($foldername, $filename = '', $filetype = '')
    {
        $this->foldername = $foldername;
        $this->dir = public_path() . "{$this->foldername}";
        $this->is_dir = false;
    }

    private function make($permission) {
        if (!file_exists($this->dir)) {
            mkdir($this->dir, file::PERMISSION[$permission], true);
        }
    }

    private function make_file($filename, $content) {
        $file = $this->dir . '/' . $filename;

        try {
            $thisfile = fopen($file, 'w');
            fwrite($thisfile, $content);
            fclose($thisfile);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function check_folder_is_exsist ($foldername, $permission, $withCreate = true) {
        $file = new file($foldername);
        $file->is_dir = false;

        if ($withCreate && !is_dir($file->dir)) {
            $file->make($permission);
        } else {
            $file->is_dir = is_dir($file->dir);
        }

        return $file;
    }
}