<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_Narcotic extends Model
{
    protected $table = 'MT_Narcotic';

    use HasFactory;

    protected $fillable = [
        'ID',
        'Narcotic_NAME',
        'Active_Status',
    ];

}
