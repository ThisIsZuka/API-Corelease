<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_LEVEL extends Model
{
    protected $table = 'MT_LEVEL';

    use HasFactory;

    protected $fillable = [
        'LEVEL_ID',
        'LEVEL',
    ];

}
