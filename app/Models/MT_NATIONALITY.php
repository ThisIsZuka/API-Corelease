<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_NATIONALITY extends Model
{
    protected $table = 'MT_NATIONALITY';
    
    use HasFactory;

    protected $fillable = [
        'Nation_ID',
        'Nationality',
    ];

   

    
}
