<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;

class API_POController extends BaseController {
    public function createPO(Request $request) {
        var_dump($request);
    }
}