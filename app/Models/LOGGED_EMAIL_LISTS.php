<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LOGGED_EMAIL_LISTS extends Model
{
    protected $table = 'LOGGED_EMAIL_LISTS';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        "id",
        "header_id",
        "message_bulkId",
        "message_transId",
        "form",
        "to",
        "user_open",
        "user_click",
        "timestamp",
        "status",
        "errors",
        "create_date",
        "update_date",
    ];

}
