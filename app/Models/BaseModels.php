<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Basemodels {
    public function __construct($table)
    {
        $this->table = $table;
    }
    public static function getInstant($table) {
        $db = new Basemodels($table);
        return $db->connect();
    }
    public function connect()
    {
        return DB::table($this->table);
    }
}