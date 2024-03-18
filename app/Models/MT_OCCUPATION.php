<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_OCCUPATION extends Model
{
    protected $table = 'MT_OCCUPATION';
    
    use HasFactory;

    protected $fillable = [
        'Ocpt_ID',
        'Ocpt_name',
        'Flag_Ocpt',
        'GROUP_INCOME',
        'GROUP_TYPE',
        'GROUP_RISK',
        'Ocpt_Active',
    ];

}
