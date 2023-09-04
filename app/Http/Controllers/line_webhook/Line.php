<?php

namespace App\Http\Controllers\line_webhook;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\line_webhook\configuration;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Storage;

class Line extends BaseController {
    public function __construct()
    {
        $config = new configuration;
    }
    function Connect () {

    }
    function webhook(Request $req) {
        return response();
    }
    function setUserID() {

    }
    function setSilentNotify() {
        
    }
    function send() {

    }
    function create_messages(){

    }
}