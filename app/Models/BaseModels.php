<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;

class BaseModels {
    public function __construct($table)
    {
        $this->table = $table;
    }
    public static function getInstant($table) {
        $db = new BaseModels($table);
        return $db->connect();
    }
    public function connect()
    {
        return DB::table($this->table);
    }
}