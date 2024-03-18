<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_MARITAL_STATUS extends Model
{
    protected $table = 'MT_MARITAL_STATUS';
    
    use HasFactory;

    protected $fillable = [
        'Mst_ID',
        'MaritalStatus',
    ];

}
