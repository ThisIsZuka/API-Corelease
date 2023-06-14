<?php

namespace App\Http\Controllers\line_webhook;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\line_webhook\configuration;

class Line extends BaseController {
    public function __construct()
    {
        $config = new configuration;
    }
    function Connect () {

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