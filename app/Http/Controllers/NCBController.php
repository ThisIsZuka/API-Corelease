<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\line_webhook\Line as Line;
use App\Http\Controllers\API_NCB_FORMATTER_v13 as NCB_formatter;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NCBController extends BaseController {
    function __construct()
    {
        $lineReq = new Line;
        $ncbformatter = new NCB_formatter;
        $this->ncb_version = $ncbformatter->version;
    }
    function getListOfFiles() {
        $public_dir = public_path() . '\\file_location\\report_ncb\\' . $this->ncb_version;
        $file = File::allFiles($public_dir);

        return view('show_txtfile', ['files' => array_chunk($file, 5)]);
    }
    function download(Request $req) {
        return response()->download($req->path);
    }
}