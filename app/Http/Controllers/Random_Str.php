<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

use Illuminate\Support\Str;

class Random_Str extends BaseController
{
    public function Random_8_str()
    {
        $random1 = Str::random(8);
        // dd($random1);
        return $random1;
    }
}
