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
        if (!empty($userInfo)) {
            return response()->json($userInfo);
        } else {
            return response()->json([
                [
                    "code" => "-1"
                    ,"message" => "Email: " . $email . " not found." 
                ]
            ]);
        }
    }
}