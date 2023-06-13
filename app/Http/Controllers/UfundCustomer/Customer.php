<?php
namespace App\Http\Controllers\UfundCustomer;

use App\Models\CustomerModels;

class Customer {
    function __construct()
    {
        $this->models = new CustomerModels();
        return $this;
    }

    function get_customer_by_email($email) {
        $userInfo = $this->models->search_userEmail($email);
        return response()->json($userInfo);
        // $userInfo = $this->models::select("EXEC [dbo].[CONTRACT_USER_BY_EMAIL] @EMAIL = ?", [$email]);
        // return response()->json($userInfo);
    }
}