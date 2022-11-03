<?php 

namespace App\Models;

use App\Models\Basemodels;

class vendor extends Basemodels {
    function __construct()
    {
        parent::__construct('Purch_VendorMaster');
    }
}