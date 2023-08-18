<?php
namespace App\Http\Controllers\UfundCustomer;

use App\Models\CustomerModels;

class Customer {
    function get_customer_by_email($email, CustomerModels $models) {
        $this->models = $models;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                [
                    "code" => "-1",
                    "message" => "Invalid email format."
                ]
            ]);
        }
        
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