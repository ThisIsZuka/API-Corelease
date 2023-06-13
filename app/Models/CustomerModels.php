<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class CustomerModels {
    function search_userEmail($email) {
        $db = DB::connection('k2prd');
        $userInfo = $db->select("EXEC [dbo].[CONTRACT_USER_BY_EMAIL] @EMAIL = ?", [$email]);
        return $userInfo;
    }
}