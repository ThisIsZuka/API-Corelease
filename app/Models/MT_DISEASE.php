<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_DISEASE extends Model
{
    protected $table = 'MT_DISEASE';

    use HasFactory;

    protected $fillable = [
        'ID',
        'Disease_NAME',
        'Active_Status',
    ];

}
