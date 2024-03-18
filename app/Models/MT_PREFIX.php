<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_PREFIX extends Model
{
    protected $table = 'MT_PREFIX';
    
    use HasFactory;

    protected $fillable = [
        'Prefix_ID',
        'Prefix_name',
    ];

   

    
}
