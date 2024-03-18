<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LOGGED_EMAIL_HEADER extends Model
{
    protected $table = 'LOGGED_EMAIL_HEADER';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        "id",
        "bulkId",
        "code",
        "message",
        "send_date",
        "checked",
    ];

}
